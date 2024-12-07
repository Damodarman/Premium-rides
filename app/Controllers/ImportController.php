<?php

namespace App\Controllers;
require_once FCPATH . '../vendor/autoload.php';
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\UberReportModel;
use App\Models\BoltReportModel;
use App\Models\MyPosReportModel;
use App\Models\DriverModel;
use App\Models\FlotaModel;
use App\Models\TvrtkaModel;
use App\Models\TaximetarReportModel;
use CodeIgniter\Files\File;
use App\Models\ActivityUberModel;
use App\Models\BoltDriverActivityModel;

use CodeIgniter\I18n\Time;

use Twilio\Rest\Client;
use App\Libraries\UltramsgLib;

class ImportController extends BaseController
{
	protected $uberReportModel;
	protected $boltReportModel;
	protected $taximetarReportModel;
	protected $myPosReportModel;

    public function __construct()
    {
		$this -> uberReportModel = new UberReportModel();
		$this -> boltReportModel = new BoltReportModel();
		$this -> taximetarReportModel = new TaximetarReportModel();
		$this -> myPosReportModel = new MyPosReportModel();
    }
	
	public function index(){
        $session = session();
		$fleet = $session->get('fleet');
        // Fetch data for the dashboard

		// Pass data to the dashboard view
		return view('appReports/reportsUpload', [
			'title' => 'Reports upload',
		]);
	}
	
public function tempUberReportImport()
{
    $session = session();
    $fleet = $session->get('fleet');
    $data = $session->get();
	$session->set('previous_url', current_url());
    // Validate the uploaded file
	$input = $this->validate([
		'file' => 'uploaded[file]|max_size[file,10240]|ext_in[file,csv]|mime_in[file,text/csv]',
	]);
    if ($this->request->getMethod() === 'post') {
        // Get the uploaded file
        $file = $this->request->getFile('file');

        if ($file !== null) {
            if ($file->getError() === UPLOAD_ERR_OK) {
                // Check if the file has a valid extension and MIME type
                if ($file->getClientMimeType() === 'text/csv') {
                    // File upload was successful and it's an Excel file
					$originalFileName = $file->getName();
					$pattern = '/(\d{2})_(\d{2})_(\d{4})/';

					// Perform the regex match
					 $originalName = $file->getClientName();
					// Extract the date from the original file name
					$year = substr($originalName, 0, 4);   // First 4 characters are the year
					$month = substr($originalName, 4, 2);  // Next 2 characters are the month
					$day = substr($originalName, 6, 2);    // Next 2 characters are the day
					$date = $year . '-' . $month . '-' . $day;

					// Calculate the week number based on the extracted date
					$week = date("W", strtotime($date));
					$week = $year . $week;
					// Generate a random name for the file
					$randomFileName = bin2hex(random_bytes(8)); // Generates a random hexadecimal string

					// Move the uploaded file to the desired location with the random name
					$file->move('../public/csvfile', $randomFileName);					
					$file = fopen("../public/csvfile/".$randomFileName,"r");
					
					$rows   = array_map('str_getcsv', file("../public/csvfile/".$randomFileName));
					//$rows = array_slice($rows, 1);
					$header_row = array_shift($rows);
					//Get the first row that is the HEADER row.
					$header_row = str_replace(['"', '﻿', '<pre>'], '', $header_row);
					$header_row = str_replace(": ", "", $header_row);
					$header_row = str_replace("'", "", $header_row);
					$header_row = str_replace("|", "", $header_row);
					$header_row = str_replace("- ", "", $header_row);
					$header_row = str_replace("€", "", $header_row);
					$header_row = str_replace("č", "c", $header_row);
					$header_row = str_replace("ć", "c", $header_row);
					$header_row = str_replace("š", "s", $header_row);
					$header_row = str_replace("ž", "z", $header_row);
					$header_row = str_replace("đ", "d", $header_row);
					$header_row = str_replace(" ", "_", $header_row);
					$header_row = str_replace(":", "_", $header_row);
					$header_row = preg_replace('/[[:cntrl:]]/', '', $header_row);
					//This array holds the final response.
					$taximetarReport    = array();
					$countReport = 0;
					foreach($rows as $row) {
						if(!empty($row)){
							$taximetarReport[] = array_combine($header_row, $row);
							$countReport++;
						}
					}
					
					foreach($taximetarReport as &$report){
						$report['fleet'] = $fleet;
						$report['week'] = $week;
						$taximetarReport1[]= array(
							'UUID_vozaca' => 'none',
							'Vozac' 			=> $report['Vozacevo_ime'] .' ' .$report['Vozacevo_prezime'],
							'Vozacevo_ime' 	=> $report['Vozacevo_ime'],
							'Vozacevo_prezime'	=> $report['Vozacevo_prezime'],
							'Ukupna_zarada' 	=> $report['Ukupna_zarada'],
							'Ukupna_zarada_Neto_cijena'	=> $report['Ukupna_zarada'],
							'Povrati_i_troskovi' 	=> 0,
							'Isplate'	=> 0,
							'Isplate_Preneseno_na_bankovni_racun' 	=> 0,
							'Isplate_Naplaceni_iznos_u_gotovini'	=> $report['Naplaceni_iznos_u_gotovini'],
							'Povrati_i_troskovi_Povrati_Pristojba_za_zracnu_luku' 	=> 0,
							'Ukupna_zarada_Napojnica'	=> 0,
							'Ukupna_zarada_Ostala_zarada_Povrat_izgubljenih_predmeta' 	=> 0,
							'Ukupna_zarada_Promocije'	=> 0,
							'Povrati_i_troskovi_Povrati_Cestarina'	=> 0,
							'fleet' 		=> $report['fleet'],
							'report_for_week' 			=> $report['week'],
						);
					}
					//$taximetarReport = array_slice($taximetarReport, 1);
					

//					echo '<pre>';
//					var_dump($taximetarReport1);
//					die();
				
				$TaximetarReportModel = new UberReportModel();
				$findRecord = $TaximetarReportModel->where('report_for_week', $week)->where('fleet', $fleet)->countAllResults();
					
				$count1 = 0;
				if($findRecord == 0){
					if($TaximetarReportModel->insertBatch($taximetarReport1)){
						$errorMessage = $countReport .' vozača je unikatno i spremljeno u bazu podataka';
						$session->setFlashdata('messageBolt', $errorMessage);
						session()->setFlashdata('alert-class', 'alert-success');
					   return redirect()->to("uberImport");
					}else{
						$errorMessage = $countReport .' vozača je unikatno ali je došlo do nepoznate pogreške prilikom spremanja u bazu podataka. Kontaktirati administratora';
						$session->setFlashdata('messageBolt', $errorMessage);
						session()->setFlashdata('alert-class', 'alert-danger');
					   return redirect()->to("uberImport");
					}
				}else{
					$updateCount = 0;
					$insertCount = 0;
					foreach($taximetarReport1 as $report){
						$existingRecord = $TaximetarReportModel->where('week', $report['week'])
													 ->where('fleet', $report['fleet'])
													 ->where('Email_vozaca', $report['Email_vozaca'])
													 ->first();

						if ($existingRecord) {
							// Data exists in the database
							if ($existingRecord['Ukupni_promet'] != $report['Ukupni_promet']) {
								// Update Ukupni_promet if different
								$TaximetarReportModel->update($existingRecord['id'], ['Ukupni_promet' => $report['Ukupni_promet']]);
								$updateCount++;
							}
							// Skip if data matches
							continue;
						}else{
							$TaximetarReportModel->insert($report);
							$insertCount++;
						}						
					}
					$errorMessage = $countReport .' vozača je na popisu od toga ' .$updateCount .' već postoji u bazi, ali su podaci ažurirani i ' .$insertCount .' nije postojalo u bazi pa su spremljeni';
					$session->setFlashdata('messageUber', $errorMessage);
					session()->setFlashdata('alert-class', 'alert-success');
				   return redirect()->to("uberImport");
				}

					
               } else {
                    // Invalid file type
                    $errorMessage = 'Invalid file type. Only .csv files are allowed.';
                    $session->setFlashdata('messageUber', $errorMessage);
                    session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("uberImport");
                }
            } else {
                // Handle the upload error
                switch ($file->getError()) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $errorMessage = 'The uploaded file exceeds the maximum file size limit.';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errorMessage = 'The uploaded file was only partially uploaded.';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $errorMessage = 'No file was uploaded.';
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $errorMessage = 'Missing a temporary folder. Upload failed.';
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $errorMessage = 'Failed to write file to disk. Upload failed.';
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $errorMessage = 'A PHP extension stopped the file upload. Upload failed.';
                        break;
                    default:
                        $errorMessage = 'Unknown upload error. Upload failed.';
                        break;
                }
                $session->setFlashdata('messageUber', $errorMessage);
                session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("uberImport");
            }
        } else {
				$errorMessage = 'No file posted';
                $session->setFlashdata('messageUber', $errorMessage);
                session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("uberImport");
        }
    }
}
	
	public function getUberReports(){
		$this -> uberReportModel = new UberReportModel();
		$session = session();
		$fleet = $session->get('fleet');

		$reports = $this->uberReportModel->where('fleet', $fleet)->get()->getResultArray();
		$weeksReports = $this->uberReportModel->select('report_for_week')->distinct()->orderBy('report_for_week','desc')->where('fleet', $fleet)->get()->getResultArray();
		$weekData = [];

		foreach ($weeksReports as $weekReport) {
			$weekString = $weekReport['report_for_week'];

			// Extract year and week from the report_for_week string
			$year = substr($weekString, 0, 4); // First 4 characters are the year
			$week = substr($weekString, 4, 2); // Last 2 characters are the week number

			// Create a DateTime object for the start of the week
			$date = new \DateTime();
			$date->setISODate($year, $week); // Set the date to the start of the given year and week

			// Get the start date (Monday) and end date (Sunday) of the week
			$startDate = $date->format('d.m.Y'); // Start of the week (Monday)
			$date->modify('+6 days');  // Add 6 days to get the end date (Sunday)
			$endDate = $date->format('d.m.Y');   // End of the week (Sunday)

			// Add the week, start date, and end date to the weekData array
			$weekData[] = [
				'week' => $weekString,
				'start_date' => $startDate,
				'end_date' => $endDate
			];
		}
		
		// Get the pagination links
		return view('appReports/uberReports', [
				'title' => 'Uber reports List',
				'reports' => $reports,
				'weekData' => $weekData,
    ]);

	}
	public function getBoltReports(){
		$this -> uberReportModel = new BoltReportModel();
		$session = session();
		$fleet = $session->get('fleet');

		$reports = $this->uberReportModel->where('fleet', $fleet)->get()->getResultArray();
		$weeksReports = $this->uberReportModel->select('report_for_week')->distinct()->orderBy('report_for_week','desc')->where('fleet', $fleet)->get()->getResultArray();
		$weekData = [];

		foreach ($weeksReports as $weekReport) {
			$weekString = $weekReport['report_for_week'];

			// Extract year and week from the report_for_week string
			$year = substr($weekString, 0, 4); // First 4 characters are the year
			$week = substr($weekString, 4, 2); // Last 2 characters are the week number

			// Create a DateTime object for the start of the week
			$date = new \DateTime();
			$date->setISODate($year, $week); // Set the date to the start of the given year and week

			// Get the start date (Monday) and end date (Sunday) of the week
			$startDate = $date->format('d.m.Y'); // Start of the week (Monday)
			$date->modify('+6 days');  // Add 6 days to get the end date (Sunday)
			$endDate = $date->format('d.m.Y');   // End of the week (Sunday)

			// Add the week, start date, and end date to the weekData array
			$weekData[] = [
				'week' => $weekString,
				'start_date' => $startDate,
				'end_date' => $endDate
			];
		}
		
		// Get the pagination links
		return view('appReports/boltReports', [
				'title' => 'Bolt reports List',
				'reports' => $reports,
				'weekData' => $weekData,
    ]);

	}
	public function getTaximetarReports(){
		$this -> taximetarReportModel = new TaximetarReportModel();
		$session = session();
		$fleet = $session->get('fleet');

		$reports = $this->taximetarReportModel->where('fleet', $fleet)->get()->getResultArray();
		$weeksReports = $this->taximetarReportModel->select('week')->distinct()->orderBy('week','desc')->where('fleet', $fleet)->get()->getResultArray();
		$weekData = [];

		foreach ($weeksReports as $weekReport) {
			$weekString = $weekReport['week'];

			// Extract year and week from the report_for_week string
			$year = substr($weekString, 0, 4); // First 4 characters are the year
			$week = substr($weekString, 4, 2); // Last 2 characters are the week number

			// Create a DateTime object for the start of the week
			$date = new \DateTime();
			$date->setISODate($year, $week); // Set the date to the start of the given year and week

			// Get the start date (Monday) and end date (Sunday) of the week
			$startDate = $date->format('d.m.Y'); // Start of the week (Monday)
			$date->modify('+6 days');  // Add 6 days to get the end date (Sunday)
			$endDate = $date->format('d.m.Y');   // End of the week (Sunday)

			// Add the week, start date, and end date to the weekData array
			$weekData[] = [
				'week' => $weekString,
				'start_date' => $startDate,
				'end_date' => $endDate
			];
		}
		
		// Get the pagination links
		return view('appReports/taximetarReports', [
				'title' => 'Taximetar reports List',
				'reports' => $reports,
				'weekData' => $weekData,
    ]);

	}
	public function getMyPosReports(){
		$session = session();
		$fleet = $session->get('fleet');

		$reports = $this->myPosReportModel->where('fleet', $fleet)->get()->getResultArray();
		$weeksReports = $this->myPosReportModel->select('report_for_week')->distinct()->orderBy('report_for_week','desc')->where('fleet', $fleet)->get()->getResultArray();
		$weekData = [];

		foreach ($weeksReports as $weekReport) {
			$weekString = $weekReport['report_for_week'];

			// Extract year and week from the report_for_week string
			$year = substr($weekString, 0, 4); // First 4 characters are the year
			$week = substr($weekString, 4, 2); // Last 2 characters are the week number

			// Create a DateTime object for the start of the week
			$date = new \DateTime();
			$date->setISODate($year, $week); // Set the date to the start of the given year and week

			// Get the start date (Monday) and end date (Sunday) of the week
			$startDate = $date->format('d.m.Y'); // Start of the week (Monday)
			$date->modify('+6 days');  // Add 6 days to get the end date (Sunday)
			$endDate = $date->format('d.m.Y');   // End of the week (Sunday)

			// Add the week, start date, and end date to the weekData array
			$weekData[] = [
				'week' => $weekString,
				'start_date' => $startDate,
				'end_date' => $endDate
			];
		}
		
		// Get the pagination links
		return view('appReports/myPosReports', [
				'title' => 'MyPos reports List',
				'reports' => $reports,
				'weekData' => $weekData,
    ]);

	}
	public function deleteUberReport()
{
    // Validate the request to ensure we received the week number
    $week = $this->request->getPost('week');
    
    if ($week) {
        // Delete all records matching the selected week
        $this->uberReportModel->where('report_for_week', $week)->delete();
        
        // Set a flash message to notify the user
        session()->setFlashdata('success', "Data for week {$week} has been successfully deleted.");

        // Redirect back to the view or dashboard
        return redirect()->to('/reports/uber');
    } else {
        // Set a flash message for the error
        session()->setFlashdata('error', 'Invalid week selected.');

        // Redirect back to the view or dashboard
        return redirect()->to('/reports/uber');
    }
}
	public function deleteBoltReport()
{
    // Validate the request to ensure we received the week number
    $week = $this->request->getPost('week');
    
    if ($week) {
        // Delete all records matching the selected week
        $this->boltReportModel->where('report_for_week', $week)->delete();
        
        // Set a flash message to notify the user
        session()->setFlashdata('success', "Data for week {$week} has been successfully deleted.");

        // Redirect back to the view or dashboard
        return redirect()->to('/reports/bolt');
    } else {
        // Set a flash message for the error
        session()->setFlashdata('error', 'Invalid week selected.');

        // Redirect back to the view or dashboard
        return redirect()->to('/reports/bolt');
    }
}
	public function deleteTaximetarReport()
{
    // Validate the request to ensure we received the week number
    $week = $this->request->getPost('week');
    
    if ($week) {
        // Delete all records matching the selected week
        $this->taximetarReportModel->where('report_for_week', $week)->delete();
        
        // Set a flash message to notify the user
        session()->setFlashdata('success', "Data for week {$week} has been successfully deleted.");

        // Redirect back to the view or dashboard
        return redirect()->to('/reports/taximetar');
    } else {
        // Set a flash message for the error
        session()->setFlashdata('error', 'Invalid week selected.');

        // Redirect back to the view or dashboard
        return redirect()->to('/reports/taximetar');
    }
}
	public function deleteMyPosReport()
{
    // Validate the request to ensure we received the week number
    $week = $this->request->getPost('week');
    
    if ($week) {
        // Delete all records matching the selected week
        $this->myPosReportModel->where('report_for_week', $week)->delete();
        
        // Set a flash message to notify the user
        session()->setFlashdata('success', "Data for week {$week} has been successfully deleted.");

        // Redirect back to the view or dashboard
        return redirect()->to('/reports/myPos');
    } else {
        // Set a flash message for the error
        session()->setFlashdata('error', 'Invalid week selected.');

        // Redirect back to the view or dashboard
        return redirect()->to('/reports/myPos');
    }
}
	
public function activityUberImport()
{
	$session = session();
    $role = $session->get('role');

    $input = $this->validate([
        'files' => 'uploaded[files.*]|max_size[files,10240]|ext_in[files,csv]|mime_in[files,text/csv]',
    ]);
    $model = new ActivityUberModel();
    $files = $this->request->getFiles();
    $successCount = 0; 
    $duplicateCount = 0;
    $errorCount = 0; 
    $session = session();

    foreach ($files['files'] as $file) {
        if (!$file->isValid()) {
            $errorCount++;
            continue; // Skip to the next file if invalid
        }

        $tempPath = $file->getTempName();
        $csvData = array_map('str_getcsv', file($tempPath));
        array_shift($csvData); // Remove header

        // Extract date from filename (add error handling)
        $filename = $file->getName();
        if (!preg_match('/(\d{4})(\d{2})(\d{2})/', $filename, $dateMatches)) {
            $errorCount++;
            continue; 
        }

        $datumUnosa = $dateMatches[1] . '-' . $dateMatches[2] . '-' . $dateMatches[3];
        $fleet = $session->get('fleet'); // Get fleet once (optimize)
        $dataToInsert = [];

        foreach ($csvData as $row) {
            list($uuid, $ime, $prezime, $voznje, $vrijemeMrezi, $vrijemeVoznje) = $row;

            // Check for duplicates efficiently
            if ($model->where('uuid_vozaca', $uuid)->where('datum_unosa', $datumUnosa)->countAllResults() > 0) {
                $duplicateCount++;
                continue;
            }

			 $dataToInsert[] = [
					'uuid_vozaca' => $uuid,
					'ime' => trim($ime),
					'prezime' => trim($prezime),
					'vozac' => trim($ime) . ' ' . trim($prezime),
					'fleet' => $fleet,
					'dovrsene_voznje' => (int)$voznje,
					'vrijeme_na_mrezi' => $this->timeToHours($vrijemeMrezi),
					'vrijeme_voznje' => $this->timeToHours($vrijemeVoznje),
					'datum_unosa' => $datumUnosa
				  ];
		}

        if (!empty($dataToInsert)) {
            $model->insertBatch($dataToInsert);
            $successCount++;
        } 
    }

    $message = "Imported: $successCount | Duplicates: $duplicateCount | Errors: $errorCount";
    $session->setFlashdata('msgActivityUber', $message);
    $session->setFlashdata('alert-class', ($errorCount > 0) ? 'alert-warning' : 'alert-success'); // Conditional alert class
	if($role != 'admin'){
		return redirect()->to("voditelj");  
	}else{
		return redirect()->to("uberImport");  
	}
}

private function timeToHours($timeStr)
{
    list($days, $hours, $minutes) = explode(':', $timeStr);
    $hours = $days * 24 + $hours + $minutes / 60; 
	$hours = round($hours, 2);
	return $hours;
}	
	
public function boltActivityImport()
    {
	
	$session = session();
    $role = $session->get('role');
    $fleet = $session->get('fleet');

    $input = $this->validate([
        'files' => 'permit_empty|max_size[files,10240]|ext_in[files,csv]|mime_in[files,text/csv,application/csv]'
    ]);

	
    if (!$input) {
        $errors = $this->validator->getErrors();
        $session->setFlashdata('msgActivityBolt', 'Validation failed for Bolt CSV upload: ' . implode(', ', $errors));
        $session->setFlashdata('alert-class', 'alert-danger');
        return redirect()->to("uberImport"); 
    }

    $model = new BoltDriverActivityModel();
    $files = $this->request->getFiles();
    $successCount = 0;
    $duplicateCount = 0;
    $errorCount = 0;

    foreach ($files['files'] as $file) {
        if ($file !== null) {  // Additional check for null file
            if ($file->getError() === UPLOAD_ERR_OK && $file->getClientMimeType() === 'text/csv') { 
                $originalFileName = $file->getName();

                // File upload was successful and it's a CSV file, proceed with processing
                $tempPath = $file->getTempName();
        // Read the first line to get headers
        $handle = fopen($tempPath, "r");
        $headers = fgetcsv($handle);
        fclose($handle);

        // Process the remaining lines
        $csvData = array_map('str_getcsv', file($tempPath));
				
					$header_row = array_shift($csvData);
					$csvData = array_slice($csvData, 1);
					$header_row = str_replace(['"', '﻿', '<pre>'], '', $header_row);
					$header_row = str_replace(": ", "", $header_row);
					$header_row = str_replace("'", "", $header_row);
					$header_row = str_replace("č", "c", $header_row);
					$header_row = str_replace("ć", "c", $header_row);
					$header_row = str_replace("š", "s", $header_row);
					$header_row = str_replace("ž", "z", $header_row);
					$header_row = str_replace("đ", "d", $header_row);
					$header_row = str_replace("(", "", $header_row);
					$header_row = str_replace(")", "", $header_row);
					$header_row = str_replace(" ", "_", $header_row);
					$header_row = preg_replace('/[[:cntrl:]]/', '', $header_row);
					$headers = str_replace(":", "_", $header_row);
                // Extract dates from filename
                preg_match('/(\d{2})_(\d{2})_(\d{4})-(\d{2})_(\d{2})_(\d{4})/', $originalFileName, $dateMatches);
                $startDate = $dateMatches[3] . '-' . $dateMatches[2] . '-' . $dateMatches[1];
                $endDate = $dateMatches[6] . '-' . $dateMatches[5] . '-' . $dateMatches[4];

                $dataToInsert = [];

                foreach ($csvData as $row) {
                    // Check for empty rows
                  if (empty(array_filter($row))) {
                        continue; // Skip to the next row
                    }
//  					print_r($row);
//					die();
                    $rowData = array_combine($headers, $row);

                 // Check for duplicates
                    if ($model->where('driver_id', $rowData['Drivers_Unique_Identifier'])
                               ->where('start_date', $startDate)
                               ->where('end_date', $endDate)
                               ->countAllResults() > 0) {
                        $duplicateCount++;
                        continue;
                    }
//				print_r($rowData);
//					die();
					
                $dataToInsert[] = [
                    'driver_id' => $rowData["Drivers_Unique_Identifier"],
                    'driver_name' => $rowData['Driver'],
                    'phone_number' => $rowData["Drivers_Phone"],
                    'email' => $rowData['Email_Address'],
                    'cash_trips_enabled' => $rowData['Cash_rides_enabled'] == 1 ? 1 : 0,
                    'driver_success_rate' => $this->cleanDecimalValue($rowData['Driver_score']),
                    'completed_rides' => (int)$rowData['Finished_Orders'],
                    'total_acceptance' => $this->cleanDecimalValue($rowData['Total_acceptance']),
                    'required_acceptance' => $this->cleanDecimalValue($rowData['Required_acceptance']),
                    'online_hours' => $this->minutesToHours($rowData['Online_Hours_min']),
                    'active_driving_hours' => $this->minutesToHours($rowData['Active_order_time_min']),
                    'utilization' => $this->cleanDecimalValue($rowData['Utilization']),
                    'rides_taken_rate' => $this->cleanDecimalValue($rowData['Completion_Rate']),
                    'rides_completed_rate' => $this->cleanDecimalValue($rowData['Finished_Rate']),
                    'average_rating' => $this->cleanDecimalValue($rowData['Average_Driver_Rating']),
                    'average_distance' => $this->metersToKilometers($rowData['Average_Ride_Distance_meters']),
                    'fleet' => $fleet,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'personal_code' => $rowData["Individual_Identifier"]
                ];
                  }

				
				
                if (!empty($dataToInsert)) {
                    $model->insertBatch($dataToInsert);
                    $successCount++;
                } else {
                    $errorCount++; 
                }

            } else {
                // File upload failed or not a CSV
                $errorCount++;
                $session->setFlashdata('msgActivityBolt', 'Invalid file type or upload error. Please upload a valid CSV file.');
                $session->setFlashdata('alert-class', 'alert-danger');
				if($role != 'admin'){
					return redirect()->to("voditelj");  
				}else{
					return redirect()->to("uberImport");  
				}
            }
        } //end of if $file!=null
    }

    $message = "Imported: $successCount | Duplicates: $duplicateCount | Errors: $errorCount";
    $session->setFlashdata('msgActivityBolt', $message);
    $session->setFlashdata('alert-class', ($errorCount > 0) ? 'alert-warning' : 'alert-success');

				if($role != 'admin'){
					return redirect()->to("voditelj");  
				}else{
					return redirect()->to("uberImport");  
				}
    }

    // Helper Methods

    private function extractFleetFromFilename($filename)
    {
        // Implement your logic to get the fleet name from the filename
        // For example, if it's always after the dates:
        $parts = explode('-', $filename);
        return trim($parts[2]); // Assuming fleet is the third part
    }

    private function metersToKilometers($meters)
    {
        return $meters / 1000;
    }

    private function minutesToHours($minutes)
    {
       return round((int)$minutes / 60, 2); 
    }

    private function cleanDecimalValue($value)
    {
        return str_replace(',', '.', $value); // Replace commas with dots for decimals
    }
	
public function taximetarImport() {
    $session = session();
    $fleet = $session->get('fleet');
    $data = $session->get();
	$session->set('previous_url', current_url());
    // Validate the uploaded file
	$input = $this->validate([
		'file' => 'uploaded[file]|max_size[file,10240]|ext_in[file,csv]|mime_in[file,text/csv]',
	]);
    if ($this->request->getMethod() === 'post') {
        // Get the uploaded file
        $file = $this->request->getFile('file');

        if ($file !== null) {
            if ($file->getError() === UPLOAD_ERR_OK) {
                // Check if the file has a valid extension and MIME type
                if ($file->getClientMimeType() === 'text/csv') {
                    // File upload was successful and it's an Excel file
					$originalFileName = $file->getName();
					$pattern = '/(\d{2})-(\d{2})-(\d{2})/';

					// Perform the regex match
					if (preg_match($pattern, $originalFileName, $matches)) {
						// Extract the matched date components
						$day = $matches[1];
						$day = $day - 2;
						$month = $matches[2];
						$year = '20' . $matches[3]; // Assuming it's 20YY format

						// Create a DateTime object
						$date = new \DateTime("$year-$month-$day");


						// Format the date as per your requirement
						$formatted_date = $date->format('Y-m-d'); // Or any other format you prefer
						$week = date("W", strtotime($formatted_date));
						$week = $year. $week;
					} else {
						echo "Date not found in the filename.";
					}
					// Generate a random name for the file
					$randomFileName = bin2hex(random_bytes(8)); // Generates a random hexadecimal string

					// Move the uploaded file to the desired location with the random name
					$file->move('../public/csvfile', $randomFileName);					
					$file = fopen("../public/csvfile/".$randomFileName,"r");
					
					$rows   = array_map('str_getcsv', file("../public/csvfile/".$randomFileName));
					$rows = array_slice($rows, 1);
					$header_row = array_shift($rows);
					//Get the first row that is the HEADER row.
					$header_row = str_replace(": ", "", $header_row);
					$header_row = str_replace("č", "c", $header_row);
					$header_row = str_replace("ć", "c", $header_row);
					$header_row = str_replace("š", "s", $header_row);
					$header_row = str_replace("ž", "z", $header_row);
					$header_row = str_replace("đ", "d", $header_row);
					$header_row = str_replace(" ", "_", $header_row);
					$header_row = str_replace(":", "_", $header_row);
					//This array holds the final response.
					$taximetarReport    = array();
					$countReport = 0;
					foreach($rows as $row) {
						if(!empty($row)){
							$taximetarReport[] = array_combine($header_row, $row);
							$countReport++;
						}
					}
					foreach($taximetarReport as &$report){
						$report['fleet'] = $fleet;
						$report['week'] = $week;
					}
	                    echo 'File uploaded successfully </br> <pre>';
					$taximetarReport = array_slice($taximetarReport, 1);
					
					foreach($taximetarReport as &$report){
						$taximetarReport1[]= array(
							'Br' 			=> $report['Br.'],
							'Ime_vozaca' 	=> $report['Ime_vozaca'],
							'Email_vozaca'	=> $report['Email_vozaca'],
							'Tel_broj' 		=> $report['Tel._broj'],
							'Ukupni_promet' => $report['Ukupni_promet_(€)'],
							'Ukupni_promet' => str_replace(",", "", $report['Ukupni_promet_(€)']),
							'fleet' 		=> $report['fleet'],
							'week' 			=> $report['week'],
						);
					}
				
				$TaximetarReportModel = new TaximetarReportModel();
				$findRecord = $TaximetarReportModel->where('week', $week)->where('fleet', $fleet)->countAllResults();
					
				$count1 = 0;
				if($findRecord == 0){
					if($TaximetarReportModel->insertBatch($taximetarReport1)){
						$errorMessage = $countReport .' vozača je unikatno i spremljeno u bazu podataka';
						$session->setFlashdata('messageTaximetar', $errorMessage);
						session()->setFlashdata('alert-class', 'alert-success');
					   return redirect()->to("uberImport");
					}else{
						$errorMessage = $countReport .' vozača je unikatno ali je došlo do nepoznate pogreške prilikom spremanja u bazu podataka. Kontaktirati administratora';
						$session->setFlashdata('messageTaximetar', $errorMessage);
						session()->setFlashdata('alert-class', 'alert-danger');
					   return redirect()->to("uberImport");
					}
				}else{
					$updateCount = 0;
					$insertCount = 0;
					foreach($taximetarReport1 as $report){
						$existingRecord = $TaximetarReportModel->where('week', $report['week'])
													 ->where('fleet', $report['fleet'])
													 ->where('Email_vozaca', $report['Email_vozaca'])
													 ->first();

						if ($existingRecord) {
							// Data exists in the database
							if ($existingRecord['Ukupni_promet'] != $report['Ukupni_promet']) {
								// Update Ukupni_promet if different
								$TaximetarReportModel->update($existingRecord['id'], ['Ukupni_promet' => $report['Ukupni_promet']]);
								$updateCount++;
							}
							// Skip if data matches
							continue;
						}else{
							$TaximetarReportModel->insert($report);
							$insertCount++;
						}						
					}
					$errorMessage = $countReport .' vozača je na popisu od toga ' .$updateCount .' već postoji u bazi, ali su podaci ažurirani i ' .$insertCount .' nije postojalo u bazi pa su spremljeni';
					$session->setFlashdata('messageTaximetar', $errorMessage);
					session()->setFlashdata('alert-class', 'alert-success');
				   return redirect()->to("uberImport");
				}

					
               } else {
                    // Invalid file type
                    $errorMessage = 'Invalid file type. Only .csv files are allowed.';
                    $session->setFlashdata('messageTaximetar', $errorMessage);
                    session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("uberImport");
                }
            } else {
                // Handle the upload error
                switch ($file->getError()) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $errorMessage = 'The uploaded file exceeds the maximum file size limit.';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errorMessage = 'The uploaded file was only partially uploaded.';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $errorMessage = 'No file was uploaded.';
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $errorMessage = 'Missing a temporary folder. Upload failed.';
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $errorMessage = 'Failed to write file to disk. Upload failed.';
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $errorMessage = 'A PHP extension stopped the file upload. Upload failed.';
                        break;
                    default:
                        $errorMessage = 'Unknown upload error. Upload failed.';
                        break;
                }
                $session->setFlashdata('messageTaximetar', $errorMessage);
                session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("uberImport");
            }
        } else {
				$errorMessage = 'No file posted';
                $session->setFlashdata('messageTaximetar', $errorMessage);
                session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("uberImport");
        }
    }
}
	
	
public function boltProcess($files, $count){
	echo $count;
	echo '<pre>';
	var_dump($files);
	die();
}
	
	
public function boltImport() {
    $session = session();
    $fleet = $session->get('fleet');
    $data = $session->get();
	$session->set('previous_url', current_url());
    // Validate the uploaded file
	$input = $this->validate([
		'file' => 'uploaded[file]|max_size[file,10240]|ext_in[file,csv]|mime_in[file,text/csv]',
	]);
    if ($this->request->getMethod() === 'post') {
        // Get the uploaded file
        $file = $this->request->getFile('file');

        if ($file !== null) {
            if ($file->getError() === UPLOAD_ERR_OK) {
                // Check if the file has a valid extension and MIME type
                if ($file->getClientMimeType() === 'text/csv') {
                    // File upload was successful and it's an Excel file
					$originalFileName = $file->getName();
					$pattern = '/(\d{2})_(\d{2})_(\d{4})/';

					// Perform the regex match
					if (preg_match($pattern, $originalFileName, $matches)) {
						// Extract the matched date components
						$day = $matches[1];
						$day = $day;
						$month = $matches[2];
						$year = $matches[3]; // Assuming it's 20YY format

						// Create a DateTime object
						$date = new \DateTime("$year-$month-$day");


						// Format the date as per your requirement
						$formatted_date = $date->format('Y-m-d'); // Or any other format you prefer
						$week = date("W", strtotime($formatted_date));
						$week = $year. $week;
					} else {
						echo "Date not found in the filename.";
					}
					// Generate a random name for the file
					$randomFileName = bin2hex(random_bytes(8)); // Generates a random hexadecimal string

					// Move the uploaded file to the desired location with the random name
					$file->move('../public/csvfile', $randomFileName);					
					$file = fopen("../public/csvfile/".$randomFileName,"r");
					
					$rows   = array_map('str_getcsv', file("../public/csvfile/".$randomFileName));
					//$rows = array_slice($rows, 1);
					$header_row = array_shift($rows);
					//Get the first row that is the HEADER row.
					$header_row = str_replace(['"', '﻿', '<pre>'], '', $header_row);
					$header_row = str_replace(": ", "", $header_row);
					$header_row = str_replace("'", "", $header_row);
					$header_row = str_replace("|", "", $header_row);
					$header_row = str_replace("- ", "", $header_row);
					$header_row = str_replace("€", "", $header_row);
					$header_row = str_replace("č", "c", $header_row);
					$header_row = str_replace("ć", "c", $header_row);
					$header_row = str_replace("š", "s", $header_row);
					$header_row = str_replace("ž", "z", $header_row);
					$header_row = str_replace("đ", "d", $header_row);
					$header_row = str_replace(" ", "_", $header_row);
					$header_row = str_replace(":", "_", $header_row);
					$header_row = preg_replace('/[[:cntrl:]]/', '', $header_row);
					//This array holds the final response.
					$taximetarReport    = array();
					$countReport = 0;
					foreach($rows as $row) {
						if(!empty($row)){
							$taximetarReport[] = array_combine($header_row, $row);
							$countReport++;
						}
					}
					foreach($taximetarReport as &$report){
						$report['fleet'] = $fleet;
						$report['week'] = $week;
						$taximetarReport1[]= array(
							'Vozac' 			=> $report['Driver'],
							'Bruto_iznos' 	=> $report['Gross_earnings'],
							'Otkazna_naknada'	=> $report['Cancellation_fees'],
							'Naknada_za_rezervaciju_placanje' 		=> $report['Booking_fees'],
							'Naknada_za_cestarinu' => $report['Toll_roads'],
							'Voznje_placene_gotovinom_prikupljena_gotovina' => $report['Cash_in_hand'],
							'Bonus' 		=> $report['Bonuses'],
							'Nadoknade' 		=> $report['Compensations'],
							'Napojnica' 		=> $report['Tips'],
							'fleet' 		=> $report['fleet'],
							'report_for_week' 			=> $report['week'],
						);
					}
					//$taximetarReport = array_slice($taximetarReport, 1);
					

//					echo '<pre>';
//					var_dump($taximetarReport1);
//					die();
				
				$TaximetarReportModel = new BoltReportModel();
				$findRecord = $TaximetarReportModel->where('report_for_week', $week)->where('fleet', $fleet)->countAllResults();
					
				$count1 = 0;
				if($findRecord == 0){
					if($TaximetarReportModel->insertBatch($taximetarReport1)){
						$errorMessage = $countReport .' vozača je unikatno i spremljeno u bazu podataka';
						$session->setFlashdata('messageBolt', $errorMessage);
						session()->setFlashdata('alert-class', 'alert-success');
					   return redirect()->to("uberImport");
					}else{
						$errorMessage = $countReport .' vozača je unikatno ali je došlo do nepoznate pogreške prilikom spremanja u bazu podataka. Kontaktirati administratora';
						$session->setFlashdata('messageBolt', $errorMessage);
						session()->setFlashdata('alert-class', 'alert-danger');
					   return redirect()->to("uberImport");
					}
				}else{
					$updateCount = 0;
					$insertCount = 0;
					foreach($taximetarReport1 as $report){
						$existingRecord = $TaximetarReportModel->where('week', $report['week'])
													 ->where('fleet', $report['fleet'])
													 ->where('Email_vozaca', $report['Email_vozaca'])
													 ->first();

						if ($existingRecord) {
							// Data exists in the database
							if ($existingRecord['Ukupni_promet'] != $report['Ukupni_promet']) {
								// Update Ukupni_promet if different
								$TaximetarReportModel->update($existingRecord['id'], ['Ukupni_promet' => $report['Ukupni_promet']]);
								$updateCount++;
							}
							// Skip if data matches
							continue;
						}else{
							$TaximetarReportModel->insert($report);
							$insertCount++;
						}						
					}
					$errorMessage = $countReport .' vozača je na popisu od toga ' .$updateCount .' već postoji u bazi, ali su podaci ažurirani i ' .$insertCount .' nije postojalo u bazi pa su spremljeni';
					$session->setFlashdata('messageBolt', $errorMessage);
					session()->setFlashdata('alert-class', 'alert-success');
				   return redirect()->to("uberImport");
				}

					
               } else {
                    // Invalid file type
                    $errorMessage = 'Invalid file type. Only .csv files are allowed.';
                    $session->setFlashdata('messageBolt', $errorMessage);
                    session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("uberImport");
                }
            } else {
                // Handle the upload error
                switch ($file->getError()) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $errorMessage = 'The uploaded file exceeds the maximum file size limit.';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errorMessage = 'The uploaded file was only partially uploaded.';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $errorMessage = 'No file was uploaded.';
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $errorMessage = 'Missing a temporary folder. Upload failed.';
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $errorMessage = 'Failed to write file to disk. Upload failed.';
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $errorMessage = 'A PHP extension stopped the file upload. Upload failed.';
                        break;
                    default:
                        $errorMessage = 'Unknown upload error. Upload failed.';
                        break;
                }
                $session->setFlashdata('messageBolt', $errorMessage);
                session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("uberImport");
            }
        } else {
				$errorMessage = 'No file posted';
                $session->setFlashdata('messageBolt', $errorMessage);
                session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("uberImport");
        }
    }
}
public function importMultipleFilesBolt()
{
    $session = session();
    $fleet = session('fleet');
    $session->set('previous_url', current_url());

    // Validate the uploaded files
    $input = $this->validate([
        'files.*' => 'uploaded[files]|max_size[files,10240]|ext_in[files,csv]|mime_in[files,text/csv]',
    ]);

    if ($this->request->getMethod() === 'post') {
        $files = $this->request->getFiles('files');

        if ($files) {
            $uploadedFiles = [];
            $processedHashes = []; // Track processed files by their content hash

            // Flatten nested arrays if necessary
            $allFiles = [];
            foreach ($files as $file) {
                if (is_array($file)) {
                    $allFiles = array_merge($allFiles, $file); // Flatten nested arrays
                } else {
                    $allFiles[] = $file;
                }
            }
			$count = 0;
            foreach ($allFiles as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
					  $taximetarReport1 = []; // Reset this variable for each file

                    // Generate a hash of the file's content to detect duplicates
                    $fileHash = hash_file('sha256', $file->getTempName());

                    if (in_array($fileHash, $processedHashes)) {
                        continue; // Skip processing if this file has already been processed
                    }

                    $processedHashes[] = $fileHash; // Mark the file as processed

					
					
                    $originalFileName = $file->getName();
                    $pattern = '/(\d{2})_(\d{2})_(\d{4})/';

                    // Perform the regex match
                    if (preg_match($pattern, $originalFileName, $matches)) {
                        // Extract the matched date components
                        $day = $matches[1];
                        $month = $matches[2];
                        $year = $matches[3];

                        // Create a DateTime object
                        $date = new \DateTime("$year-$month-$day");

                        // Format the date as per your requirement
                        $formatted_date = $date->format('Y-m-d');
                        $week = $year . date("W", strtotime($formatted_date));
                    } else {
                        echo "Date not found in the filename.";
                        continue;
                    }

                    $randomFileName = bin2hex(random_bytes(8)) . '.csv';
                    $file->move('../public/csvfile', $randomFileName);
                    $filePath = "../public/csvfile/" . $randomFileName;

                    // Process the CSV
                    $rows = array_map('str_getcsv', file($filePath));
                    $header_row = array_shift($rows);

                    // Sanitize the header row
                    $header_row = $this->sanitizeHeaderRow($header_row);
                    $taximetarReport = [];
                    foreach ($rows as $row) {
                        if (!empty($row)) {
                            $taximetarReport[] = array_combine($header_row, $row);
                        }
                    }

                    foreach ($taximetarReport as &$report) {
                        $report['fleet'] = $fleet;
                        $report['week'] = $week;

                        $taximetarReport1[] = [
                            'Vozac' => $report['Driver'],
                            'Bruto_iznos' => $report['Gross_earnings'] ?? 0,
                            'Otkazna_naknada' => $report['Cancellation_fees'] ?? 0,
                            'Naknada_za_rezervaciju_placanje' => $report['Booking_fees'] ?? 0,
                            'Naknada_za_cestarinu' => $report['Toll_roads'] ?? 0,
                            'Voznje_placene_gotovinom_prikupljena_gotovina' => $report['Cash_in_hand'] ?? 0,
                            'Bonus' => $report['Bonuses'] ?? 0,
                            'Nadoknade' => $report['Compensations'] ?? 0,
                            'Napojnica' => $report['Tips'] ?? 0,
                            'fleet' => $report['fleet'],
                            'report_for_week' => $report['week'],
                        ];
                    }
                    $uploadedFiles[] = $taximetarReport1;
                }
            }
            // Combine arrays by Vozac and sum numeric values
            $finalCombinedArray = [];
            foreach ($uploadedFiles as $fileArray) {
                foreach ($fileArray as $entry) {
                    $vozacKey = $entry['Vozac'];

                    if (!isset($finalCombinedArray[$vozacKey])) {
                        // If Vozac doesn't exist in the final array, add it
                        $finalCombinedArray[$vozacKey] = $entry;
                    } else {
                        // Sum the numeric values for existing Vozac
							$finalCombinedArray[$vozacKey]['Bruto_iznos'] = round($finalCombinedArray[$vozacKey]['Bruto_iznos'] + $entry['Bruto_iznos'], 2);
							$finalCombinedArray[$vozacKey]['Otkazna_naknada'] = round($finalCombinedArray[$vozacKey]['Otkazna_naknada'] + $entry['Otkazna_naknada'], 2);
							$finalCombinedArray[$vozacKey]['Naknada_za_rezervaciju_placanje'] = round($finalCombinedArray[$vozacKey]['Naknada_za_rezervaciju_placanje'] + $entry['Naknada_za_rezervaciju_placanje'], 2);
							$finalCombinedArray[$vozacKey]['Naknada_za_cestarinu'] = round($finalCombinedArray[$vozacKey]['Naknada_za_cestarinu'] + $entry['Naknada_za_cestarinu'], 2);
							$finalCombinedArray[$vozacKey]['Voznje_placene_gotovinom_prikupljena_gotovina'] = round($finalCombinedArray[$vozacKey]['Voznje_placene_gotovinom_prikupljena_gotovina'] + $entry['Voznje_placene_gotovinom_prikupljena_gotovina'], 2);
							$finalCombinedArray[$vozacKey]['Bonus'] = round($finalCombinedArray[$vozacKey]['Bonus'] + $entry['Bonus'], 2);
							$finalCombinedArray[$vozacKey]['Nadoknade'] = round($finalCombinedArray[$vozacKey]['Nadoknade'] + $entry['Nadoknade'], 2);
							$finalCombinedArray[$vozacKey]['Napojnica'] = round($finalCombinedArray[$vozacKey]['Napojnica'] + $entry['Napojnica'], 2);                    }
                }
            }

            // Reset keys to ensure the final array is indexed properly
            $finalCombinedArray = array_values($finalCombinedArray);
//			echo '<pre>';
//			var_dump($finalCombinedArray);
//			die();
            // Process final combined data
				$TaximetarReportModel = new BoltReportModel();
				$findRecord = $TaximetarReportModel->where('report_for_week', $week)->where('fleet', $fleet)->countAllResults();
			if($findRecord === 0){
				$TaximetarReportModel->insertBatch($finalCombinedArray);
				$session->setFlashdata('messageBolt', 'Files successfully uploaded.');
				$session->setFlashdata('alert-class', 'alert-success');
				return redirect()->to("uberImport");
			}else{
				$session->setFlashdata('messageBolt', 'Već postoji u bazi.');
				$session->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to("uberImport");
			}
        } else {
            $session->setFlashdata('messageBolt', 'No valid files uploaded.');
            $session->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to("multipleBoltFileUpload");
        }
    }
}
public function importMultipleFilesUber()
{
    $session = session();
    $fleet = session('fleet');
    $session->set('previous_url', current_url());

    // Validate the uploaded files
    $input = $this->validate([
        'files.*' => 'uploaded[files]|max_size[files,10240]|ext_in[files,csv]|mime_in[files,text/csv]',
    ]);

    if ($this->request->getMethod() === 'post') {
        $files = $this->request->getFiles('files');

        if ($files) {
            $uploadedFiles = [];
            $processedHashes = []; // Track processed files by their content hash

            // Flatten nested arrays if necessary
            $allFiles = [];
            foreach ($files as $file) {
                if (is_array($file)) {
                    $allFiles = array_merge($allFiles, $file); // Flatten nested arrays
                } else {
                    $allFiles[] = $file;
                }
            }
			$count = 0;
            foreach ($allFiles as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
					  $taximetarReport1 = []; // Reset this variable for each file

                    // Generate a hash of the file's content to detect duplicates
                    $fileHash = hash_file('sha256', $file->getTempName());

                    if (in_array($fileHash, $processedHashes)) {
                        continue; // Skip processing if this file has already been processed
                    }

                    $processedHashes[] = $fileHash; // Mark the file as processed

					
					
                    $original_name = $file->getName();
						$year = substr($original_name,0,4);
						$month = substr($original_name,4,2);
						$day = substr($original_name,6,2);
						$date = $year.'-'.$month.'-'.$day;
						$week = date("W", strtotime($date));
						$week = $year. $week;
                    } else {
                        echo "Date not found in the filename.";
                        continue;
                    }

                    $randomFileName = bin2hex(random_bytes(8)) . '.csv';
                    $file->move('../public/csvfile', $randomFileName);
                    $filePath = "../public/csvfile/" . $randomFileName;

                    // Process the CSV
                    $rows = array_map('str_getcsv', file($filePath));
                    $header_row = array_shift($rows);

                    // Sanitize the header row
    			$header_row = str_replace(['"', '﻿', '<pre>'], '', $header_row);
				$header_row = str_replace(": ", "", $header_row);
				$header_row = str_replace("č", "c", $header_row);
				$header_row = str_replace("ć", "c", $header_row);
				$header_row = str_replace("š", "s", $header_row);
				$header_row = str_replace("ž", "z", $header_row);
				$header_row = str_replace("đ", "d", $header_row);
				$header_row = str_replace(" ", "_", $header_row);
				$header_row = str_replace(":", "_", $header_row);
				
                    $taximetarReport = [];
                    foreach ($rows as $row) {
                        if (!empty($row)) {
                            $taximetarReport[] = array_combine($header_row, $row);
                        }
                    }
//				echo '<pre>';
//				var_dump($header_row);
//				die();
                    foreach ($taximetarReport as &$report) {
                        $report['fleet'] = $fleet;
                        $report['week'] = $week;

						$taximetarReport1[]= array(
							'UUID_vozaca' => $report['UUID_vozaca'],
							'Vozac' 			=> $report['Vozacevo_ime'] .' ' .$report['Vozacevo_prezime'],
							'Vozacevo_ime' 	=> $report['Vozacevo_ime'],
							'Vozacevo_prezime'	=> $report['Vozacevo_prezime'],
							'Ukupna_zarada' 	=> floatval($report['Ukupna_zarada']),
							'Ukupna_zarada_Neto_cijena'	=> floatval($report['Ukupna_zarada_Neto_cijena']),
							'Povrati_i_troskovi' 	=> floatval($report['Povrati_i_troskovi']),
							'Isplate'	=> floatval($report['Isplate']),
							'Isplate_Preneseno_na_bankovni_racun' 	=> floatval($report['Isplate_Preneseno_na_bankovni_racun']),
							'Isplate_Naplaceni_iznos_u_gotovini'	=> floatval($report['Isplate_Naplaceni_iznos_u_gotovini']),
							'Povrati_i_troskovi_Povrati_Pristojba_za_zracnu_luku' 	=> floatval($report['Povrati_i_troskovi_Povrati_Pristojba_za_zracnu_luku']),
							'Ukupna_zarada_Napojnica'	=> floatval($report['Ukupna_zarada_Napojnica']),
							'Ukupna_zarada_Promocije'	=> floatval($report['Ukupna_zarada_Promocije']),
							'Povrati_i_troskovi_Povrati_Cestarina'	=> floatval($report['Povrati_i_troskovi_Povrati_Cestarina']),
							'fleet' 		=> $report['fleet'],
							'report_for_week' 			=> $report['week'],
						);
                    }
                    $uploadedFiles[] = $taximetarReport1;
                }
            }
            // Combine arrays by Vozac and sum numeric values
            $finalCombinedArray = [];
            foreach ($uploadedFiles as $fileArray) {
                foreach ($fileArray as $entry) {
                    $vozacKey = $entry['Vozac'];

                    if (!isset($finalCombinedArray[$vozacKey])) {
                        // If Vozac doesn't exist in the final array, add it
                        $finalCombinedArray[$vozacKey] = $entry;
                    } else {
                        // Sum the numeric values for existing Vozac
							$finalCombinedArray[$vozacKey]['Ukupna_zarada'] = round($finalCombinedArray[$vozacKey]['Ukupna_zarada'] + $entry['Ukupna_zarada'], 2);
							$finalCombinedArray[$vozacKey]['Ukupna_zarada_Neto_cijena'] = round($finalCombinedArray[$vozacKey]['Ukupna_zarada_Neto_cijena'] + $entry['Ukupna_zarada_Neto_cijena'], 2);
							$finalCombinedArray[$vozacKey]['Povrati_i_troskovi'] = round($finalCombinedArray[$vozacKey]['Povrati_i_troskovi'] + $entry['Povrati_i_troskovi'], 2);
							$finalCombinedArray[$vozacKey]['Isplate'] = round($finalCombinedArray[$vozacKey]['Isplate'] + $entry['Isplate'], 2);
							$finalCombinedArray[$vozacKey]['Isplate_Preneseno_na_bankovni_racun'] = round($finalCombinedArray[$vozacKey]['Isplate_Preneseno_na_bankovni_racun'] + $entry['Isplate_Preneseno_na_bankovni_racun'], 2);
							$finalCombinedArray[$vozacKey]['Isplate_Naplaceni_iznos_u_gotovini'] = round($finalCombinedArray[$vozacKey]['Isplate_Naplaceni_iznos_u_gotovini'] + $entry['Isplate_Naplaceni_iznos_u_gotovini'], 2);
							$finalCombinedArray[$vozacKey]['Povrati_i_troskovi_Povrati_Pristojba_za_zracnu_luku'] = round($finalCombinedArray[$vozacKey]['Povrati_i_troskovi_Povrati_Pristojba_za_zracnu_luku'] + $entry['Povrati_i_troskovi_Povrati_Pristojba_za_zracnu_luku'], 2);
							$finalCombinedArray[$vozacKey]['Ukupna_zarada_Napojnica'] = round($finalCombinedArray[$vozacKey]['Ukupna_zarada_Napojnica'] + $entry['Ukupna_zarada_Napojnica'], 2);                    
							$finalCombinedArray[$vozacKey]['Ukupna_zarada_Promocije'] = round($finalCombinedArray[$vozacKey]['Ukupna_zarada_Promocije'] + $entry['Ukupna_zarada_Promocije'], 2);                    

						$finalCombinedArray[$vozacKey]['Povrati_i_troskovi_Povrati_Cestarina'] = round($finalCombinedArray[$vozacKey]['Povrati_i_troskovi_Povrati_Cestarina'] + $entry['Povrati_i_troskovi_Povrati_Cestarina'], 2);                    
					}
                }
            }

            // Reset keys to ensure the final array is indexed properly
            $finalCombinedArray = array_values($finalCombinedArray);
            // Process final combined data
				$TaximetarReportModel = new UberReportModel();
				$findRecord = $TaximetarReportModel->where('report_for_week', $week)->where('fleet', $fleet)->countAllResults();
			if($findRecord === 0){
				$TaximetarReportModel->insertBatch($finalCombinedArray);
				$session->setFlashdata('messageUber', 'Files successfully uploaded.');
				$session->setFlashdata('alert-class', 'alert-success');
				return redirect()->to("uberImport");
			}else{
				$session->setFlashdata('messageUber', 'Već postoji u bazi.');
				$session->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to("uberImport");
			}
        } else {
            $session->setFlashdata('messageUber', 'No valid files uploaded.');
            $session->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to("multipleBoltFileUpload");
        }
    }

private function sanitizeHeaderRow($header_row)
{
    $header_row = str_replace(['"', '﻿', '<pre>'], '', $header_row);
    $header_row = str_replace(": ", "", $header_row);
    $header_row = str_replace("'", "", $header_row);
    $header_row = str_replace("|", "", $header_row);
    $header_row = str_replace("- ", "", $header_row);
    $header_row = str_replace("€", "", $header_row);
    $header_row = str_replace(["č", "ć", "š", "ž", "đ"], ["c", "c", "s", "z", "d"], $header_row);
    $header_row = str_replace(" ", "_", $header_row);
    $header_row = str_replace(":", "_", $header_row);
    $header_row = preg_replace('/[[:cntrl:]]/', '', $header_row);
    return $header_row;
}

}