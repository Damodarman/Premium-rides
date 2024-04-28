<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\FlotaModel;
use App\Models\TvrtkaModel;
use App\Models\UltramsgLibConfigModel;
  
class FlotaController extends Controller
{
    public function index()
    {
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$role = $session->get('role');

		
		$flotaModel = new FlotaModel();
		$tvrtkaModel = new TvrtkaModel();
		$ultramsgLibConfigModel = new UltramsgLibConfigModel();
		
		$postavkeFlote = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$tvrtke = $tvrtkaModel->where('fleet', $fleet)->get()->getResultArray();
		$aktivnaTvrtka = $tvrtkaModel->where('id', $postavkeFlote['tvrtka_id'])->get()->getRowArray();
		$UltramsgLibConf = $ultramsgLibConfigModel->where('fleet_id', $postavkeFlote['id'])->get()->getRowArray();
		
		
		$data['UltramsgLibConf'] = $UltramsgLibConf;
		$data['flota'] = $postavkeFlote;
		$data['role'] = $role;
		$data['tvrtke'] = $tvrtke;
		$data['aktivnaTvrtka'] = $aktivnaTvrtka;
		$data['fleet'] = $fleet;
		$data['page'] = 'Postavke Flote';
		
		return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/flota')		
			. view('footer');

        
    }
	
	public function UltramsgLibPostavke(){
        $session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$ultramsgLibConfigModel = new UltramsgLibConfigModel();
		$flotaModel = new FlotaModel();
		$tvrtkaModel = new TvrtkaModel();
		$tvrtke = $tvrtkaModel->where('fleet', $fleet)->get()->getResultArray();
		$postavkeFlote = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$aktivnaTvrtka = $tvrtkaModel->where('id', $postavkeFlote['tvrtka_id'])->get()->getRowArray();
		$UltramsgLibConf = $ultramsgLibConfigModel->where('fleet_id', $postavkeFlote['id'])->get()->getRowArray();
		
		// Validate the form data here if needed
        $validationRules = [
            'api_url' => 'required',
            'Instance_ID' => 'required',
            'Token' => 'required',
        ];

        if ($this->validate($validationRules)) {
            // Form data is valid, proceed to save
            $UltramsgLibConfModel = new UltramsgLibConfigModel();
            
            $data = [
                'api_url' => $this->request->getPost('api_url'),
                'Instance_ID' => $this->request->getPost('Instance_ID'),
                'Token' => $this->request->getPost('Token'),
                'fleet_id' => $this->request->getPost('fleet_id'),
				'id' => $this->request->getPost('id')
            ];

            // Check if there's an 'id' value, and determine whether to insert or update
                $UltramsgLibConfModel->save($data);

            return redirect()->to(base_url('/index.php/admin/flota'));
        } else {
            // Form validation failed, show errors
		$data['input'] = [
                'api_url' => $this->request->getPost('api_url'),
                'Instance_ID' => $this->request->getPost('Instance_ID'),
                'Token' => $this->request->getPost('Token'),
                'fleet_id' => $this->request->getPost('fleet_id'),
            ];
 		$data['UltramsgLibConf'] = $UltramsgLibConf;
		$data['flota'] = $postavkeFlote;
		$data['role'] = $role;
		$data['tvrtke'] = $tvrtke;
		$data['aktivnaTvrtka'] = $aktivnaTvrtka;
		$data['fleet'] = $fleet;
		$data['page'] = 'Postavke Flote';
		
		return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/flota', ['validation' => $this->validator, ])		
			. view('footer');
        }
		
	}
	
	public function postavkeFloteUpdate(){
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');

		
		$flotaModel = new FlotaModel();
		$tvrtkaModel = new TvrtkaModel();
		
		$data = array();
		$tvrtka_id = $this->request->getVar('tvrtka_id');
		$tvrtka = $tvrtkaModel->where('id', $tvrtka_id)->get()->getRowArray();
//		print_r($tvrtka);
//		die();
		$flotaId = $this->request->getVar('flota_id');
		
		$data = [
			'id'  => $this->request->getVar('flota_id'),
			'naziv'  => $this->request->getVar('naziv'),
			'vlasnik'  => $this->request->getVar('vlasnik'),
			'vlasnik_users_id'  => $this->request->getVar('vlasnik_users_id'),
			'fiskalizacija_bolt'  => $this->request->getVar('fiskalizacija_bolt'),
			'fiskalizacija_uber'  => $this->request->getVar('fiskalizacija_uber'),
			'provizija_fiks'  => $this->request->getVar('provizija_fiks'),
			'provizija_postotak'  => $this->request->getVar('provizija_postotak'),
			'taximetar'  => $this->request->getVar('taximetar'),
			'taximetar_tjedno'  => $this->request->getVar('taximetar_tjedno'),
			'koristi_min_proviziju'  => $this->request->getVar('koristi_min_proviziju'),
			'iznos_min_provizije'  => $this->request->getVar('iznos_min_provizije'),
			'tvrtka_naziv'  => $tvrtka['naziv'],
			'tvrtka_id'  => $tvrtka['id'],
		];
		
		if($flotaModel->update($flotaId, $data)){
			$session->setFlashdata('msgflota', ' Uspješno ažurirane postavke flote.');
			session()->setFlashdata('alert-class', 'alert-success');
			return redirect()->to('/index.php/admin/flota');
		} else{
			$session->setFlashdata('msgflota', ' Došlo je do pogreške, nismo ažurirali postavke flote.');
			session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to('/index.php/admin/flota');
		}
	}
}