<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\FlotaModel;
use App\Models\TvrtkaModel;
  
class TvrtkaController extends Controller
{
	public function index(){
		helper('form');
		$session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		
		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();
		$columns = $tvrtkaModel->get()->getFieldNames('tvrtka');
		$columns = array_diff($columns, ['id']); // Remove 'id' field
		$postavkeFlote = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$tvrtke = $tvrtkaModel->where('fleet', $fleet)->get()->getResultArray();
		$aktivnaTvrtka = $tvrtkaModel->where('id', $postavkeFlote['tvrtka_id'])->get()->getRowArray();

		
		$data['role'] = $role;
		$data['fleet'] = $fleet;
		$data['page'] = 'Tvrtke';
		$data['aktivnaTvrtka'] = $aktivnaTvrtka;
		$data['tvrtke'] = $tvrtke;
		$data['columns'] = $columns;
		
		echo view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/tvrtka')
			. view('footer');

		
	}
	
	
	public function addTvrtka() {
		helper('form');
		$session = session();
		$fleet = $session->get('fleet');

		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();

		$request = service('request');
		$validationRules = [
			'naziv' => 'required|min_length[1]|max_length[100]',
			'adresa' => 'required|min_length[1]|max_length[100]',
			'postanskiBroj' => 'required|exact_length[5]|numeric',
			'grad' => 'required',
			'država' => 'required',
			'OIB' => 'required|regex_match[/^\d{11}$/]',
			'direktor' => 'required',
			'fleet' => 'required',
			'pocetak_tvrtke' => 'required',
		];

		// Validate the form input
		if (!$this->validate($validationRules)) {
			// If validation fails, display error messages and redisplay the form with user input
			$data['validation'] = $this->validator;
			foreach ($validationRules as $inputName => $rule) {
				$data['formData'][$inputName] = $request->getPost($inputName);
			}
			$columns = $tvrtkaModel->get()->getFieldNames('tvrtka');
			$columns = array_diff($columns, ['id']); // Remove 'id' field
			$postavkeFlote = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
			$tvrtke = $tvrtkaModel->where('fleet', $fleet)->get()->getResultArray();
			$aktivnaTvrtka = $tvrtkaModel->where('id', $postavkeFlote['tvrtka_id'])->get()->getRowArray();
			$data['fleet'] = $fleet;
			$data['page'] = 'Tvrtke';
			$data['aktivnaTvrtka'] = $aktivnaTvrtka;
			$data['tvrtke'] = $tvrtke;
			$data['columns'] = $columns;

			echo view('adminDashboard/header', $data)
				. view('adminDashboard/navBar')
				. view('adminDashboard/tvrtka')
				. view('footer');
		} else {
			// If validation succeeds, process the form data and redirect to a success page
			// Fill the formData array with user input data
			foreach ($validationRules as $inputName => $rule) {
				$tvrtka[$inputName] = $request->getPost($inputName);
			}
			// Process the form data and redirect to a success page
			if($tvrtkaModel->save($tvrtka)){
				$session->setFlashdata('msgtvrtka', ' Uspješno dodana tvrtka.');
				session()->setFlashdata('alert-class', 'alert-success');
				return redirect()->to('admin/flota');
			}
			else{
				$session->setFlashdata('msgtvrtka', ' Tvrtka nije dodana.');
				session()->setFlashdata('alert-class', 'alert-danger');
				echo view('adminDashboard/header', $data)
					. view('adminDashboard/navBar')
					. view('adminDashboard/tvrtka')
					. view('footer');
				
			}
		}
	}

}