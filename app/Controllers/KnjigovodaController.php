<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\PrijaveModel;
  
class KnjigovodaController extends Controller
{
    public function index()
    {
		$prijaveModel = new PrijaveModel();
        $session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		
		$neobradenePrijave = $prijaveModel
			->where('fleet', $fleet)
			->where('obradeno', 0)
			->orderBy('timestamp', 'DESC')
			->groupBy('vozac_id')
			->get()->getResultArray();
        $obradenePrijave = $prijaveModel
			->where('fleet', $fleet)
			->where('obradeno', 1)
			->orderBy('timestamp', 'DESC')
			->groupBy('vozac_id')
			->get()
			->getResultArray();

        // Get the current page from the URL, or default to the first page
		
		$data['page'] = 'Dashboard knjigovođe';
		$data['fleet'] = $fleet;
		$data['role'] = $role;
		$data['neobradenePrijave'] = $neobradenePrijave;
		$data['obradenePrijave'] = $obradenePrijave;
		
		echo view('adminDashboard/header', $data)
			.view('adminDashboard/navBar')
			.view('adminDashboard/knjigovoda');
       
    }
	
	public function obradenoDa(){
        $session = session();
		$prijaveModel = new PrijaveModel();
		$obradeno = 1;
		$obradeno_timestamp = date('Y-m-d');
		$data =[
			'obradeno_timestamp' =>$obradeno_timestamp,
			'obradeno' =>$obradeno,
		];
		
		
		$id = $this->request->getVar('id');
		$vozac = $this->request->getVar('vozac');
		$data['id'] = $id;
		//$data['vozac'] = $vozac;
//		print_r($data);
//		die();
		
		if($prijaveModel->update($id, $data)){
			$session->setFlashdata('msgPoruka', "Uspješno izmjenjen status radnika $vozac na obrađeno.");
			session()->setFlashdata('alert-class', 'alert-success');
			return redirect()->to('/index.php/knjigovoda');
		}else{
			$session->setFlashdata('msgPoruka', "Nije uspjela izmjena status radnika $vozac na obrađeno. Javiti administratoru");
			session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to('/index.php/knjigovoda');
		}
		
	}
}