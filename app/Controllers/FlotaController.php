<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\FlotaModel;
use App\Models\TvrtkaModel;
use App\Models\UltramsgLibConfigModel;

use App\Libraries\MsgTemplateLib;

  
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
		
		$allMsgTmpl = $this->getMsgTmplAll();
		$dobrodoslica = $this->getMsgTmpl('Dobrodošlica');
		$dug1 = $this->getMsgTmpl('dug1');
		
		
		$data['UltramsgLibConf'] = $UltramsgLibConf;
		$data['flota'] = $postavkeFlote;
		$data['role'] = $role;
		$data['tvrtke'] = $tvrtke;
		$data['allMsgTmpl'] = $allMsgTmpl;
		$data['dobrodoslica'] = $dobrodoslica;
		$data['dug1'] = $dug1;
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

            return redirect()->to(site_url('admin/flota'));
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
	
	public function getMsgTmplAll(){
		$MsgTmplLib = new MsgTemplateLib();
		$allMsg = $MsgTmplLib->getAll();
		return $allMsg;
	}
	
	public function getMsgTmpl($data){
		$MsgTmplLib = new MsgTemplateLib();
		$allMsg = $MsgTmplLib->getMsgTmpl($data);
		return $allMsg;
	}
	
	public function saveMsgTmpl(){
        $session = session();
		$MsgTmplLib = new MsgTemplateLib();
		$data = [
			'id'  => $this->request->getVar('id'),
			'name'  => $this->request->getVar('name'),
			'content'  => $this->request->getVar('content'),
		];
		
		$saveMsg = $MsgTmplLib->saveMsgTmpl($data);
		if($saveMsg != 0){
			$session->setFlashdata('msgflota', ' Uspješno ažuriran template poruke.');
			session()->setFlashdata('alert-class', 'alert-success');
			return redirect()->to(site_url('admin/flota'));
		}else{
			$session->setFlashdata('msgflota', ' Došlo je do pogreške, nismo ažurirali template poruke.');
			session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to(site_url('admin/flota'));
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
			'koristiti_taximetar_whatsapp'  => $this->request->getVar('koristiti_taximetar_whatsapp'),
			'taximetar_whatsapp_broj'  => $this->request->getVar('taximetar_whatsapp_broj'),
			'koristi_activity'  => $this->request->getVar('koristi_activity'),
			'koristi_min_proviziju'  => $this->request->getVar('koristi_min_proviziju'),
			'koristi_min_proviziju_sezona'  => $this->request->getVar('koristi_min_proviziju_sezona'),
			'provizija_fiks_sezona'  => $this->request->getVar('provizija_fiks_sezona'),
			'iznos_min_provizije'  => $this->request->getVar('iznos_min_provizije'),
			'minimalna_provizija_sezona'  => $this->request->getVar('minimalna_provizija_sezona'),
			'tvrtka_naziv'  => $tvrtka['naziv'],
			'tvrtka_id'  => $tvrtka['id'],
		];
		
		if($flotaModel->update($flotaId, $data)){
			$session->setFlashdata('msgflota', ' Uspješno ažurirane postavke flote.');
			session()->setFlashdata('alert-class', 'alert-success');
			return redirect()->to('admin/flota');
		} else{
			$session->setFlashdata('msgflota', ' Došlo je do pogreške, nismo ažurirali postavke flote.');
			session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to('admin/flota');
		}
	}
}