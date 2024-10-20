<?php

namespace App\Controllers;
require_once FCPATH . '../vendor/autoload.php';
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\UberReportModel;
use App\Models\BoltReportModel;
use App\Models\MyPosReportModel;
use App\Models\DriverModel;
use App\Models\ObracunModel;
use App\Models\FiskalizacijaModel;
use App\Models\ObracunFirmaModel;
use App\Models\FlotaModel;
use App\Models\TvrtkaModel;
use App\Models\ReferalBonusModel;
use App\Models\VozilaModel;
use App\Models\DoprinosiModel;
use App\Models\DugoviModel;
use App\Models\NapomeneModel;
use App\Models\PrijaveModel;

use Twilio\Rest\Client;
use App\Libraries\UltramsgLib;

class PrijaveController extends BaseController
{
	public function index(){
		$customSettings = [
			'table_open' => '<table border="1" cellpadding="2" cellspacing="1" class="table">',
		];

		$table = new \CodeIgniter\View\Table($customSettings);
		$prijaveModel = new PrijaveModel();
		
		$session = session();
		$fleet = $session->get('fleet');
		$data = $session->get();
		
		$driverModel = new DriverModel();
		
		$vozaci = $driverModel->select('id, ime, prezime, OIB, dob, adresa, grad, drzava, pocetak_prijave, vrsta_zaposlenja, kraj_prijave, broj_sati, IBAN, zasticeniIBAN, strani_IBAN, radno_mjesto') ->where('fleet', $fleet)->where('aktivan', 1)->where('prijava', '1')->get()->getResultArray();

		$prijave = $driverModel->select('id, vozac, OIB, dob, adresa, grad, drzava, vrsta_zaposlenja, kraj_prijave, pocetak_prijave, broj_sati, IBAN, zasticeniIBAN, strani_IBAN, radno_mjesto') ->where('fleet', $fleet)->get()->getResultArray();
		
		$radnici1 = array();
		//$prvi_unos = array();
		foreach($prijave as $prijava){

			$prvi_unos = $prijaveModel->where('vozac_id', $prijava['id'])->where('prvi_unos', 1)->get()->getRowArray();
			
			
			if (!isset($prvi_unos['broj_sati'])) {
					// Log or print the information to identify the problematic record

					// You may want to handle the error or continue to the next iteration
					continue;
				}		
			
			
			$zadnja_promjena = $prijaveModel->where('vozac_id', $prijava['id'])->where('nadopuna', 1)->orderBy('id', 'DESC')->get()->getRowArray();
			if($zadnja_promjena != null){
				$pocetak_promjene = $zadnja_promjena['pocetak_promjene'];
				$promjena_sati = $zadnja_promjena['broj_sati'];
				$prekid_rada = $zadnja_promjena['prekid_rada'];
			}else{
				$pocetak_promjene = 'nema';
				$promjena_sati = 'nema';
				$prekid_rada = 'nema';
			}
			
			
			
			$radnici1[]= [
				'vozac' => $prijava['vozac'],
				'OIB' => $prijava['OIB'],
				'dob' => $prijava['dob'],
				'adresa' => $prijava['adresa'],
				'grad' => $prijava['grad'],
				'drzava' => $prijava['drzava'],
				'vrsta_zaposlenja' => $prijava['vrsta_zaposlenja'],
				'pocetak_prijave' => $prijava['pocetak_prijave'],
				'kraj_prijave' => $prijava['kraj_prijave'],
				'broj_sati' => $prvi_unos['broj_sati'],
				'IBAN' => $prijava['IBAN'],
				'zasticeniIBAN' => $prijava['zasticeniIBAN'],
				'strani_IBAN' => $prijava['strani_IBAN'],
				'radno_mjesto' => $prijava['radno_mjesto'],
				'sati_nakon_promjene' => $promjena_sati,
				'pocetak_promjene' => $pocetak_promjene,				
				'prekid_rada' => $prekid_rada,				
			];

		}
		
		
usort($radnici1, function ($a, $b) {
    $dateA_prijave = strtotime($a['pocetak_prijave']);
    $dateB_prijave = strtotime($b['pocetak_prijave']);

    $dateA_promjene = strtotime($a['pocetak_promjene']);
    $dateB_promjene = strtotime($b['pocetak_promjene']);

    // Compare based on the latest date, considering both "pocetak_prijave" and "pocetak_promjene"
    $latestDateA = max($dateA_prijave, $dateA_promjene);
    $latestDateB = max($dateB_prijave, $dateB_promjene);

    return $latestDateB - $latestDateA;
});

		$radnici2 = array();

		foreach($radnici1 as $radnik){
			
			// Original date in YYYY-MM-DD format
			$originalDate = $radnik['pocetak_prijave'];

			// Create a DateTime object from the original date
			$dateTime = \DateTime::createFromFormat('Y-m-d', $originalDate);

			// Format the date in DD/MM/YYYY format
			$formattedDate = $dateTime->format('d/m/Y');
			
			
			//$formattedDatePromjena = $radnik['pocetak_promjene'];
			if($radnik['pocetak_promjene'] != null && $radnik['pocetak_promjene'] != 'nema'){
				$originalDate1 = $radnik['pocetak_promjene'];

				// Create a DateTime object from the original date
				$dateTime = \DateTime::createFromFormat('Y-m-d', $originalDate1);

				// Format the date in DD/MM/YYYY format
				$formattedDatePromjena = $dateTime->format('d/m/Y');
			}else{
				$formattedDatePromjena = '00/00/0000';
			}
			if($radnik['kraj_prijave'] != null && $radnik['kraj_prijave'] != 'nema' && $radnik['kraj_prijave'] != '0000-00-00'){
				$originalDate1 = $radnik['kraj_prijave'];

				// Create a DateTime object from the original date
				$dateTime = \DateTime::createFromFormat('Y-m-d', $originalDate1);

				// Format the date in DD/MM/YYYY format
				$formattedDateKraj = $dateTime->format('d/m/Y');
			}else{
				$formattedDateKraj = '-';
			}
			
			$formattedDatePrekid = null;
			if($radnik['prekid_rada'] != null && $radnik['prekid_rada'] != 'nema'){
				$originalDate = $radnik['prekid_rada'];

				// Create a DateTime object from the original date
				$dateTime = \DateTime::createFromFormat('Y-m-d', $originalDate);

				// Format the date in DD/MM/YYYY format
				$formattedDatePrekid = $dateTime->format('d/m/Y');
				
			}

			
			$radnici2[]= [
				'vozac' => $radnik['vozac'],
				'OIB' => $radnik['OIB'],
				'dob' => $radnik['dob'],
				'adresa' => $radnik['adresa'],
				'grad' => $radnik['grad'],
				'drzava' => $radnik['drzava'],
				'vrsta_zaposlenja' => $prijava['vrsta_zaposlenja'],
				'kraj_prijave' => $formattedDateKraj,
				'pocetak_prijave' => $formattedDate,
				'broj_sati' => $radnik['broj_sati'],
				'IBAN' => $radnik['IBAN'],
				'zasticeniIBAN' => $radnik['zasticeniIBAN'],
				'strani_IBAN' => $radnik['strani_IBAN'],
				'radno_mjesto' => $radnik['radno_mjesto'],
				'sati_nakon_promjene' => $radnik['sati_nakon_promjene'],
				'pocetak_promjene' => $formattedDatePromjena,
				'prekid_rada' => $formattedDatePrekid,				

			];

			
		}
		
		$radnici = $prijaveModel->select('prijave.*, users.name as administrator')
			->join('users', 'users.id = prijave.user_id', 'left')
			->where('prijave.fleet', $fleet)
			->orderBy('prijave.id', 'DESC')
			->get()
			->getResultArray();
		
		$data['page'] = 'Prijave Radnika';
		$data['fleet'] = $fleet;
		
		
		foreach($vozaci as &$vozac){
			$vozac['vozac_id'] = $vozac['id'];
			$vozac['prvi_unos'] = 1;
			$vozac['nadopuna'] = 0;
			$vozac['fleet'] = $fleet;
			$vozac['user_id'] = $data['id'];
		}
		$data['radnici2'] = $radnici2;
		$data['radnici1'] = $radnici1;
		$data['radnici'] = $radnici;
		$data['prijave'] = $prijave;
//		print_r($vozaci);
		//$prijaveModel->insertBatch($vozaci);
//		
		
        return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/prijaveRadnika')		
			. view('footer');
	}
	

	
	
	
}