<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\PrijaveModel;
use App\Models\DriverModel;
  
class KnjigovodaController extends Controller
{
    public function index()
    {
		$prijaveModel = new PrijaveModel();
        $session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		
		$neobradenePrijave = $prijaveModel->where('obradeno', 0)
			->where('fleet', $fleet)
			->orderBy('timestamp', 'DESC')
			->get()
			->getResultArray();

		$obradenePrijave = $prijaveModel->select('prijave.*')
			->join('(
				SELECT vozac_id, MAX(timestamp) as max_timestamp
				FROM prijave
				WHERE obradeno = 1
				GROUP BY vozac_id
			) as latest', 'latest.vozac_id = prijave.vozac_id AND latest.max_timestamp = prijave.timestamp')
			
			 ->where('prijave.fleet', $fleet)
			->orderBy('prijave.obradeno_timestamp', 'DESC')
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
	
	public function activDrivers(){
		
        $session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		
		$driverModel = new DriverModel();
		
		$activeDrivers = $driverModel->select('ime, prezime, dob, oib, pocetak_prijave, broj_sati, radno_mjesto')->where('fleet', $fleet)->where('prijava', 1)->where('aktivan', 1)->get()->getResultArray();
		$activeDrivers1 = $driverModel->where('fleet', $fleet)->where('prijava', 1)->where('aktivan', 1)->get()->getResultArray();
		
		$data['page'] = 'Aktivni radnici danas';
		$data['fleet'] = $fleet;
		$data['role'] = $role;
		$data['activeDrivers'] = $activeDrivers;
		$data['activeDrivers1'] = $activeDrivers1;
		
		echo view('adminDashboard/header', $data)
			.view('adminDashboard/navBar')
			.view('adminDashboard/aktivniDanas');
	}
	
	public function obradenoDa(){
        $session = session();
		$prijaveModel = new PrijaveModel();
		$obradeno = 1;
		$obradeno_timestamp = date('Y-m-d');
		$id = $this->request->getVar('id');
		$vozac = $this->request->getVar('vozac');
		$data =[
			'id'=> $id,
			'obradeno_timestamp' =>$obradeno_timestamp,
			'obradeno' =>$obradeno, 
		];
		
//		print_r($data);
//		die();
		//$data['vozac'] = $vozac;
		
		if($prijaveModel->where('id', $id)->update($id, $data)){
			$session->setFlashdata('msgPoruka', "Uspješno izmjenjen status radnika $vozac na obrađeno.");
			session()->setFlashdata('alert-class', 'alert-success');
			return redirect()->to('knjigovoda');
		}else{
			$session->setFlashdata('msgPoruka', "Nije uspjela izmjena status radnika $vozac na obrađeno. Javiti administratoru");
			session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to('knjigovoda');
		}
		
	}
}