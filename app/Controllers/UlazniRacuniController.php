<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Config\Database;
use CodeIgniter\Database\Migration;
use Config\Services;
use App\Database\Migrations\CreateTables;
use CodeIgniter\Database\MigrationRunner;
use App\Models\RacuniModel;
use App\Models\TrgovciModel;
use App\Models\PrMjTrModel;



class UlazniRacuniController extends Controller{
	
	public function index(){
		$session = session();
		$role = $session->get('role');
		$fleet = $session->get('fleet');
		$data['page'] = 'Ulazni Racuni';
		$data['fleet'] = $fleet;
		$data['role'] = $role;
		
		echo view('adminDashboard/header', $data)
			. view('adminDashboard/knjigovodstvoNavbar')
			. view('adminDashboard/ulazniRacuni')
			. view('footer');

	}
	
	public function unosRacuna(){
		$session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$racuniModel = new RacuniModel();
		$trgovciModel = new TrgovciModel();
		$tableFields = $racuniModel->get()->getFieldNames();
		$latestInput = $racuniModel->orderBy('id', 'DESC')->first();
		$tableFields = array_diff($tableFields, ['id']);
		$trgovci = $trgovciModel->get()->getResultArray();
		
		
		$data['trgovci'] = $trgovci;
		$data['page'] = 'Unos Racuna';
		$data['role'] = $role;
		$data['fleet'] = $fleet;
		$data['formFields'] = $tableFields;
		$data['brojDokumenta'] = $latestInput['broj_dokumenta'] + 1;
		

		echo view('adminDashboard/header', $data)
			. view('adminDashboard/knjigovodstvoNavbar')
			. view('adminDashboard/unosRacuna')
			. view('footer');
	}
	
	public function saveRacuna(){
		$session = session();
		
		$racuniModel = new RacuniModel();
		$request = \Config\Services::request();
		$racun = $request->getPost();
		$dodatni_opis_napomena_dokumenta = $racun['dodatni_opis_napomena_dokumenta'];
		$count = $racuniModel->where('dodatni_opis_napomena_dokumenta', $dodatni_opis_napomena_dokumenta)->countAllResults();
		$idTrgovca = $racun['komitent_id'];
		$date = $racun['datum_dokumenta'];
		if($racun['konto_s_dokumenta'] == 4077){
			$this->addProdajnoMjesto($dodatni_opis_napomena_dokumenta, $idTrgovca, $date);
		}
		
		
		if($count != 0){
				$session->setFlashdata('msgRacuni', ' Račun broj ' .$dodatni_opis_napomena_dokumenta .' već postoji u bazi podataka.');
				session()->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to('/index.php/unosRacuna/');	
		}else{
			
			if($racuniModel->save($racun)){
				$session->setFlashdata('msgRacuni', ' Uspješno unesen račun.');
				session()->setFlashdata('alert-class', 'alert-success');
				return redirect()->to('/index.php/unosRacuna/');	
			}else{
				$session->setFlashdata('msgRacuni', ' Došlo je do problema sa unosom, pokušajte ponovo ili kontaktirajte administratora.');
				session()->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to('/index.php/unosRacuna/');	
			}
		}
				
	}
	
	public function addProdajnoMjesto($value, $idTrgovca, $date){
		$session = session();
		$PrMjTrModel = new PrMjTrModel();
		if (strpos($value, '/') !== false) {
				$separator = '/';
			} elseif (strpos($value, '-') !== false) {
				$separator = '-';
			}

		list($brojRacuna, $prodajnoMjesto) = explode($separator, $value, 2);
		$prodajnoMjesto = $separator .$prodajnoMjesto;
		$countPrMj = $PrMjTrModel->where('mjestoBroj', $prodajnoMjesto)->countAllResults();
		if($countPrMj > 0){
			// vec postoji prodajno mjesto
				$session->setFlashdata('msgProdajnoMjesto', ' Prodajno mjesto već postoji u bazi.');
				session()->setFlashdata('alert-class', 'alert-info');
			return('true');
		}else{
			// treba dodati prodajno mjesto
			$firstDateOfYear = date('Y-01-01', strtotime($date)); // Get the first date of the year
			$daysPassed = floor((strtotime($date) - strtotime($firstDateOfYear)) / (60 * 60 * 24)); // Calculate the number of days passed
			$averagePerDay = $brojRacuna / ($daysPassed + 1);
			$averagePerDay = floor($averagePerDay);
			
			$dataProdajnoMjesto['trgovci_id'] = $idTrgovca;
			$dataProdajnoMjesto['mjestoBroj'] = $prodajnoMjesto;
			$dataProdajnoMjesto['poDanu'] = $averagePerDay;
			if($PrMjTrModel->save($dataProdajnoMjesto)){
				$session->setFlashdata('msgProdajnoMjesto', ' Prodajno mjesto dodano u bazu.');
				session()->setFlashdata('alert-class', 'alert-success');
				return('true');
			}else{
				$session->setFlashdata('msgProdajnoMjesto', ' Prodajno mjesto nije dodano.');
				session()->setFlashdata('alert-class', 'alert-danger');
				return('true');
			}
		}

	}
	
	public function getRacuni(){
		$table = new \CodeIgniter\View\Table();
		$session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$data['role'] = $role;
		$data['page'] = 'Ulazni Racuni';
		$data['fleet'] = $fleet;

		$start_date = $this->request->getVar('start_date');
		$end_date = $this->request->getVar('end_date');
		$racuniModel = new RacuniModel();
		
		$racuni = $racuniModel->where('datum_dokumenta >=', $start_date)
                          ->where('datum_dokumenta <=', $end_date)
                          ->findAll();	
		
		foreach($racuni as &$racun){
			 unset($racun['id']);
			 $racun['devizni_iznos_dokumenta'] = str_replace('.', ',', $racun['devizni_iznos_dokumenta']);
			 $racun['iznos_robe_bez_PDV'] = str_replace('.', ',', $racun['iznos_robe_bez_PDV']);
			 $racun['iznos_robe_s_PDV-om'] = str_replace('.', ',', $racun['iznos_robe_s_PDV-om']);
			 $racun['iznos_računa_s_PDV'] = str_replace('.', ',', $racun['iznos_računa_s_PDV']);
			 $racun['ukupan_iznos_PDV'] = str_replace('.', ',', $racun['ukupan_iznos_PDV']);
			 $racun['nepriznata_osnovica_za_tarifni_broj_8'] = str_replace('.', ',', $racun['nepriznata_osnovica_za_tarifni_broj_8']);
			 $racun['osnovica_za_tarifni_broj_6'] = str_replace('.', ',', $racun['osnovica_za_tarifni_broj_6']);
			 $racun['iznos_poreza_za_tarifni_broj_6'] = str_replace('.', ',', $racun['iznos_poreza_za_tarifni_broj_6']);
			$date = \DateTime::createFromFormat('Y-m-d', $racun['datum_dokumenta']);
			$racun['datum_dokumenta'] = $date->format('d.m.Y');
			$date = \DateTime::createFromFormat('Y-m-d', $racun['datum_računa']);
			$racun['datum_računa'] = $racun['datum_dokumenta'];
			$date = \DateTime::createFromFormat('Y-m-d', $racun['datum_dospijeća']);
			$racun['datum_dospijeća'] = $racun['datum_dokumenta'];
			$date = \DateTime::createFromFormat('Y-m-d', $racun['datum_kreiranja']);
			$racun['datum_kreiranja'] = $racun['datum_dokumenta'];
		}
//		echo '<pre>';
//		print_r($racuni);
//		die();
		
		$data['role'] = $role;
		$data['racuni'] = $racuni;
		$template = [
			'table_open' => '<table id="DataTable" border="0" cellpadding="4" cellspacing="0">',
			// ... rest of the template ...
		];

		$table->setTemplate($template);

		$data['table'] = $table->generate($racuni);
		
		echo view('adminDashboard/header', $data)
			. view('adminDashboard/knjigovodstvoNavbar')
			. view('adminDashboard/ulazniRacuni')
			. view('footer');
	}
	
}