<?php

namespace App\Controllers;

use App\Models\ObracunModel;
use App\Models\TvrtkaModel;
use App\Models\DriverModel;
use App\Models\FlotaModel;


use App\Libraries\SepaGenerator\SEPAService;
use App\Services\IbanValidationService;


class TjedneIsplateController extends BaseController
{
    protected $obracunModel;
    protected $tvrtkaModel;
	protected $driverModel;
	protected $flotaModel;

    public function __construct()
    {
        // Load models here (in case you're not using dependency injection)
        $this->obracunModel = new ObracunModel();
        $this->tvrtkaModel = new TvrtkaModel();
        $this->driverModel = new DriverModel();
        $this->flotaModel = new FlotaModel();
    }
 
 
/**
 * Fetch driver data from the database based on the driver ID and combine it with the amount from AJAX.
 */
private function fetchDbDriverData($id, $zaIsplatu, &$driversWithoutIBAN)
{
    // Fetch driver from the database
    $vozac = $this->driverModel->where('id', $id)->get()->getRowArray();
    if (!$vozac) {
        throw new \Exception("Driver with ID $id not found.");
    }

    // Use IBAN service if needed
    $ibanService = new IbanValidationService();

    // Get the relevant IBAN, prioritize HR bank IBANs
	// Set the IBAN using the hierarchy: IBAN -> zasticeniIBAN -> strani_IBAN
	if($vozac['tjedna_isplata'] === 'hrIBAN'){$iban = $vozac['IBAN']; }
	if($vozac['tjedna_isplata'] === 'Revolut'){$iban = $vozac['strani_IBAN']; }
	if($vozac['tjedna_isplata'] === 'zasticeniIBAN'){$iban = $vozac['zasticeniIBAN']; }

	$bic = null;
	$validIban = $ibanService->validate($iban);
	// Check if the IBAN is specifically from strani_IBAN and set the Revolut BIC
	if ($iban === $vozac['strani_IBAN']) {
		$bic = 'REVOLT21';  // Revolut BIC for foreign IBAN
	} else 

	// If no valid IBAN, store the driver in a separate array and skip this driver
	if (!$ibanService) {
		$driversWithoutIBAN[] = $vozac;  // Store driver without IBAN
		return null;  // Do not include this driver in the SEPA generation
	}


    // Generate the payment reference
    $paymentReference = $vozac['id'];

    // Prepare and return driver data for SEPA transfer, including the amount from AJAX
    return [
        'creditorIBAN' => $iban,
        'bic' => $bic,
        'amount' => $zaIsplatu,  // Amount to be paid from AJAX
        'reference' => 'HR99',
        'creditorName' => $vozac['ime'] .' ' .$vozac['prezime'],
        'description' => 'Loko vožnja',
            'creditorAddress' => [
                'adresa' => $vozac['adresa'] .', ' .$vozac['postanskiBroj'] .', ' .$vozac['grad'],
                'country' => 'HR'
            ]
    ];
}

/**
 * Fetch driver data for SEPA generation (data from AJAX).
 */
private function fetchAjaxDriverData()
{
    // Fetch JSON data from the POST request (drivers selected via AJAX)
    $ajaxDriverData = $this->request->getJSON(true);

    if (empty($ajaxDriverData)) {
        throw new \Exception('No drivers selected for SEPA processing.');
    }

    $dbDriverData = [];  // Data collected from the database

    // Loop through each driver ID and collect the necessary data from the database
    foreach ($ajaxDriverData['drivers'] as $driverInfo) {
        $driverId = (int)$driverInfo['vozac_id'];  // Ensure the ID is cast to an integer
        $zaIsplatu = floatval($driverInfo['zaIsplatu']);  // Get amount from AJAX request

        $driverData = $this->fetchDbDriverData($driverId, $zaIsplatu, $driversWithoutIBAN);  // Fetch driver info from the database and include the amount
        
        // Only add the driver if valid (i.e., if they have an IBAN)
        if ($driverData) {
            $dbDriverData[] = $driverData;
        }
    }

    return $dbDriverData;  // Return the data fetched from the database
}

	
	private function getCompany(){
        $session = session();
        $fleet = $session->get('fleet');
		$flotaInfo = $this->flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$company = $this->tvrtkaModel->where('id', $flotaInfo['tvrtka_id'])->get()->getRowArray();
		return $company;
	}
/**
 * Generate SEPA file for HR bank payments.
 */
public function generirajHRSepa()
{
	$company = $this->getCompany();
	$adresa = $company['adresa'] .', ' .$company['postanskiBroj'] .', ' .$company['grad'];
	$companyOIB= $company['OIB'];
    // Dummy company information (Debtor)
    $companyName = $company['naziv'];
    $companyIBAN = $company['IBAN'];
    $companyBIC = $company['BIC'];
    $companyAddress = [
        'street' => 'Company Street',
		'adressLine' => $adresa,
        'buildingNumber' => '1',
        'postalCode' => '10000',
        'town' => 'Zagreb',
        'country' => 'HR'
    ];
    
    // Debtor's Poziv na broj
    $debtorReference = 'HR6940002-' .$companyOIB .'-240';  // Example Poziv na broj for the debtor

    // Initialize SEPA Service (pass debtor's Poziv na broj)
    $sepaService = new SEPAService(
        'HR-Payment-' . date('Ymd-His'),
        $companyName,
        $companyIBAN,
        $companyBIC,
        $companyAddress,
        $debtorReference  // Pass Poziv na broj for Debtor
    );

	$creditors = $this->fetchAjaxDriverData();


    // Add each creditor's data to the SEPA file
    foreach ($creditors as $creditor) {
		if(!empty($creditor['creditorIBAN'])){
			$sepaService->addTransaction(
				$creditor['amount'],
				$creditor['creditorIBAN'],
				$creditor['creditorName'],
				$creditor['reference'],  // Pass Creditor's Poziv na broj
				$creditor['description'],
				$creditor['creditorAddress']
			);
		}
    }

    // Generate the SEPA XML file
    $xmlContent = $sepaService->generateXML();

    // Save the XML file and return it as a download
    $filePath = WRITEPATH . 'uploads/hr_payment_sepa.xml';
    file_put_contents($filePath, $xmlContent);

    return $this->response->download($filePath, null)->setFileName('HR_Payment_SEPA.xml'); 
}


	
	
	/**
     * Displays the form for weekly payouts.
     */
    public function tjedneIsplate($week = null)
    {
        // Start the session
        $session = session();
        $data = $session->get();

        // Fetch necessary data
        $fleet = $session->get('fleet');
        $obracunData = $this->obracunModel
            ->select('obracun.vozac_id, obracun.zaIsplatu, vozaci.ime, vozaci.prezime, vozaci.tjedna_isplata, vozaci.IBAN, vozaci.zasticeniIBAN, vozaci.strani_IBAN')
            ->join('vozaci', 'obracun.vozac_id = vozaci.id')
            ->where('obracun.zaIsplatu >', 0)
            ->where('obracun.fleet', $fleet)
            ->where('obracun.week', $week)
            ->get()
            ->getResultArray();

        // Prepare data for the views
        $data['page'] = 'Tjedne Isplate';
        $data['obracun'] = $obracunData;
        $data['fleet'] = $fleet;

        // Render the views
        echo view('adminDashboard/header', $data)
            . view('adminDashboard/navBar')
            . view('adminDashboard/tjedneIsplateForm', $data) 
            . view('footer');
    }
	
public function generirajRevolutCSV()
{
    // Get the POST data from the request
    $requestData = $this->request->getJSON(true); // Convert JSON to associative array
    $drivers = $requestData['drivers'];
    // Create a temporary file to store the CSV data

    $csvFilePath = tempnam(sys_get_temp_dir(), 'revolut_') . '.csv';

    // Open the file for writing
    $file = fopen($csvFilePath, 'w');

    // Write the header row to the CSV
    fputcsv($file, ['Name', 'Recipient type', 'IBAN', 'BIC', 'Recipient bank country', 'Currency', 'Amount', 'Payment reference', 'Recipient country', 'Address line 1', 'Address line 2', 'City', 'Postal code']);

    // Loop through the drivers and add their data to the CSV
    foreach ($drivers as $driver) {
		$name = $driver['ime'] . ' ' . $driver['prezime'];
	$name = $this->replaceCroatianSpecialChars($name);
		
        $id = $driver['vozac_id'];
        $driverDataRevolut = $this->driverDataRevolut($id);
        fputcsv($file, [
            $name,   // Full name
            'individual',                                // Recipient type
            $driverDataRevolut['IBAN'],                  // IBAN
            $driverDataRevolut['BIC'],                   // BIC
            $driverDataRevolut['recipientCountry'],      // Recipient bank country (from IBAN)
            'EUR',                                       // Currency
            $driver['zaIsplatu'],                        // Amount to be paid
            'LOKO VOZNJA',                               // Payment reference
            'HR',      // Recipient country (from IBAN)
            $driverDataRevolut['address'],               // Address line 1
            '',                                          // Address line 2 (optional)
            $driverDataRevolut['city'],                  // City
            $driverDataRevolut['postCode'],              // Postal code
        ]);
    }

    // Close the file
    fclose($file);

    // Send the file as a downloadable response
    return $this->response->download($csvFilePath, null)->setFileName('revolut_bulk_payment.csv');
}

function replaceCroatianSpecialChars($string) {
    // Define the special characters and their replacements
    $replacements = array(
        'š' => 's',
        'đ' => 'd',
        'č' => 'c',
        'ć' => 'c',
        'ž' => 'z',
        'Š' => 'S',
        'Đ' => 'D',
        'Č' => 'C',
        'Ć' => 'C',
        'Ž' => 'Z'
    );

    // Replace the characters
    return strtr($string, $replacements);
}
	

private function driverDataRevolut($id)
{
    try {
        // Fetch driver details
		$driverModel = new DriverModel();
        $driver = $driverModel->where('id', $id)->get()->getRowArray();

        if (!$driver) {
            echo "Driver not found for ID: $id";
        }

        $ibanService = new IbanValidationService();

        // Prioritize 'strani_IBAN', fallback to 'zasticeniIBAN' and 'IBAN'
        $iban = $driver['strani_IBAN'];

        // Check if IBAN exists
        if (!$iban) {
            throw new Exception("IBAN not found for ID: $id");
        }

        // Address and Post Code
        $drzava = $driver['drzava'] ?: 'Unknown Address';  // Fallback to default value
        $address = $driver['adresa'] ?: 'Unknown Address';  // Fallback to default value
        $city = $driver['grad'] ?: 'Unknown City';
        $postCode = $driver['postanskiBroj'] ?: '10000';  // Fallback if postcode is 0

        // Replace Croatian special characters
        $address = $this->replaceCroatianSpecialChars($address);
        $drzava = $this->replaceCroatianSpecialChars($drzava);
        $city = $this->replaceCroatianSpecialChars($city);

        // Get the BIC and country from the IBAN
        $recipientCountry = $ibanService->getRecipientCountry($iban);

        // Construct driver data for CSV
        return [
            'IBAN' => $iban,
            'BIC' => 'REVOLT21',
            'recipientCountry' => $recipientCountry,
            'drzava' => $drzava,
            'address' => $address,
            'city' => $city,
            'postCode' => $postCode,
        ];
    } catch (Exception $e) {
        // Log the error for further analysis (adjust according to your logging mechanism)
        log_message('error', $e->getMessage());
        return ['error' => $e->getMessage()];
    }
}	
	
}
