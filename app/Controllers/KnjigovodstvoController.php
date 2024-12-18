<?php

namespace App\Controllers;
		//require 'vendor/autoload.php';


use CodeIgniter\Controller;
use App\Models\TrgovciModel;
use App\Models\PrMjTrModel;
use Dompdf\Adapter\CPDF;
use Dompdf\FontMetrics;


class KnjigovodstvoController extends Controller
{

	public function index(){


		$session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$data['page'] = 'Knjigovodstvo';
		$data['fleet'] = $fleet;
		$data['role'] = $role;
		
		echo view('adminDashboard/header', $data)
		. view('adminDashboard/knjigovodstvoNavbar')
		. view('adminDashboard/knjigovodstvo')
		. view('footer');
	}
	
	
	public function addprodajnomjesto(){
		
		$trgovciModel = new TrgovciModel();
		$session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$data['role'] = $role;
		$data['page'] = 'Dodaj prodajno mjesto trgovca';
		$data['fleet'] = $fleet;
		$data['trgovci'] = $trgovciModel->get()->getResultArray();
		
		echo view('adminDashboard/header', $data)
		. view('adminDashboard/knjigovodstvoNavbar')
		. view('adminDashboard/dodajPrMjTr')
		. view('footer');
	}
	
	public function addPrMjTrSave(){
		$session = session();
		$fleet = $session->get('fleet');
		helper(['form']);
		$data = [        
			'trgovci_id' => $this->request->getVar('trgovci_id'),
			'mjestoBroj' => $this->request->getVar('mjestoBroj'), 
			'poDanu' => $this->request->getVar('poDanu'),
		];
		
		$PrMjTrModel = new PrMjTrModel();

		// Check if the data already exists
		$existingData = $PrMjTrModel->where('mjestoBroj', $data['mjestoBroj'])->first();

		if ($existingData) {
			$session->setFlashdata('msgProdajnoMjesto', ' Prodajno mjesto već postoji u bazi podataka.');
			session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to('addprodajnomjesto');
		} else {
			if($PrMjTrModel->save($data)){
				$session->setFlashdata('msgProdajnoMjesto', ' Uspješno dodano Prodajno mjesto.');
				session()->setFlashdata('alert-class', 'alert-success');
				return redirect()->to('addprodajnomjesto');
			} else {
				$session->setFlashdata('msgProdajnoMjesto', ' Neuspješno dodano Prodajno mjesto.');
				session()->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to('addprodajnomjesto');
			}
		}
	
	}
	
	public function addTrgovca(){
		$session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$data['role'] = $role;
		$data['page'] = 'Dodaj trgovca';
		$data['fleet'] = $fleet;
		
		echo view('adminDashboard/header', $data)
		. view('adminDashboard/knjigovodstvoNavbar')
		. view('adminDashboard/addTrgovca')
		. view('footer');
		
	}
	
	
	
	
	public function addTrgovcaSave(){
		$session = session();
		$fleet = $session->get('fleet');
		helper(['form']);

		$data = [        
			'nazivTrgovca' => $this->request->getVar('nazivTrgovca'),
			'adresaTrgovca' => $this->request->getVar('adresaTrgovca'),
			'gradTrgovca' => $this->request->getVar('gradTrgovca'), 
			'porezniBrojTrgovca' => $this->request->getVar('porezniBrojTrgovca'),
			'postanskiBroj' => $this->request->getVar('postanskiBroj'),
		];

		$trgovciModel = new TrgovciModel();

		// Check if the data already exists
		$existingData = $trgovciModel->where('nazivTrgovca', $data['nazivTrgovca'])->first();

		if ($existingData) {
			$session->setFlashdata('msgTrgovac', ' Trgovac već postoji u bazi podataka.');
			session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to('addTrgovca');
		} else {
			if($trgovciModel->save($data)){
				$session->setFlashdata('msgTrgovac', ' Uspješno dodan trgovac.');
				session()->setFlashdata('alert-class', 'alert-success');
				return redirect()->to('addTrgovca');
			} else {
				$session->setFlashdata('msgTrgovac', ' Neuspješno dodan trgovac.');
				session()->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to('addTrgovca');
			}
		}

		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}