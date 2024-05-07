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
use App\Models\DugoviNaplataModel;
use App\Models\PrijaveModel;
use App\Models\NapomeneModel;

use CodeIgniter\HTTP\ResponseInterface;
use Twilio\Rest\Client;
use App\Libraries\UltramsgLib;
use Dompdf\Dompdf;

class AdminController extends BaseController
{
	 private $twilio;
    
    public function __construct()
    {
	}
    
    public function sendSms()
    {
        $session = session();
		$to = $this->request->getVar('broj');
        $message = $this->request->getVar('msg');
        if($this->twilio->sendSms($to, $message)){
			$session->setFlashdata('msgPoruka', ' Uspješno poslana poruka.');
			session()->setFlashdata('alert-class', 'alert-success');
			return redirect()->to('/index.php/admin/');
		}
		else{
			$session->setFlashdata('msgPoruka', ' Nije poslana poruka.');
			session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to('/index.php/admin/');
		}
		
    }
	
	public function getTestData(){
        $session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$obracunModel = new ObracunModel();
		$table = new \CodeIgniter\View\Table();
		
		$obracuni = $obracunModel->select('vozac, doprinosi, cetvrtinaNetoPlace')->where('fleet', 'Rocky Rider')->where('week', '202351')->get()->getResultArray();
		$data['page'] = 'Napomene';
		$data['fleet'] = $fleet;
		$data['role'] = $role;
		$data['table'] = $table->generate($obracuni);
		
		
		
        return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/getTestData')		
			. view('footer');
	}
	
	public function napomene(){
        $session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		
		$napomeneModel = new NapomeneModel();
		
		$napomene = $napomeneModel->select('napomene.*, vozaci.vozac as driver_name')
			->join('vozaci', 'vozaci.id = napomene.driver_id')
			->where('napomene.fleet', $fleet)
			->orderBy('napomene.timestamp', 'DESC') // Add this line to order by timestamp in descending order
			->get()
			->getResultArray();		
		
		$data['page'] = 'Napomene';
		$data['fleet'] = $fleet;
		$data['role'] = $role;
		$data['napomene'] = $napomene;

        return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/napomene')		
			. view('footer');
	}
	
    public function index()
    {
		
        $session = session();
		$fleet = $session->get('fleet');
		$data = $session->get();
		$direktor = $data['name'];
		$dugoviNaplataModel = new DugoviNaplataModel();
		$tvrtkaModel = new TvrtkaModel();
		$tvrtkaModel = new TvrtkaModel();
		$vozaciModel = new DriverModel();
		$obracunFirmaModel = new ObracunFirmaModel();
		$tvrtka = $tvrtkaModel->where('fleet', $fleet)->get()->getRowArray();
		$vozaciAktivni = $vozaciModel->where('fleet', $fleet)->where('aktivan', '1')->countAllResults();
		$vozaciNeaktivni = $vozaciModel->where('fleet', $fleet)->where('aktivan', '0')->countAllResults();
		$vozaciPrijava = $vozaciModel->where('fleet', $fleet)->where('aktivan', '1')->where('prijava', '1')->countAllResults();
		$week = $obracunFirmaModel->selectMax('week')->where('fleet', $fleet)->get()->getRowArray();
		//$naplaceniDugovi = $dugoviNaplataModel->where('fleet', $fleet)->get()->getResultArray();
		$weekbefore = (int)$week['week'];
		if($weekbefore != 202401){
			$weekbefore = $weekbefore -1;
		}else{
			$weekbefore = 202352;
		}
		$zadnjiObracun = $obracunFirmaModel->where('fleet', $fleet)->where('week', $week)->get()->getRowArray();
		$predzadnjiObracun = $obracunFirmaModel->where('fleet', $fleet)->where('week', $weekbefore)->get()->getRowArray();
		$firmaObracuni = $obracunFirmaModel->where('fleet', $fleet)->get()->getResultArray();
		$weeklyData = $this->weeklyData($firmaObracuni);
		
		
		
		//$this->sendSMS();
		$data['page'] = 'Dashboard';
		$data['zadnjiObracun'] = $zadnjiObracun;
		$data['predzadnjiObracun'] = $predzadnjiObracun;
		$data['fleet'] = $fleet;
		$data['weeklyData'] = $weeklyData;
		$data['tvrtka'] = $tvrtka;
		$data['vozaci'] = $vozaciAktivni;
		$data['vozaciPrijava'] = $vozaciPrijava;
		$data['vozaciNeAktivni'] = $vozaciNeaktivni;
		//$data['naplaceniDugovi'] = $naplaceniDugovi;
		
        return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/admin')		
			. view('adminDashboard/putNovca')		
			. view('adminDashboard/dashboard_chart')		
			. view('footer');
    }
	
	
	private function weeklyData($data){
//		$weeklyData['uberNeto'][] = 0;
//		$weeklyData['boltNeto'][] = 0;
//		$weeklyData['ukupnoNeto'][] = 0;
//		$weeklyData['gotovina'][] = 0;
//		$weeklyData['zaradaFirme'][] = 0;
//		$weeklyData['week'][] = 0;
		
		foreach($data as $week){
			$weeklyData['uberNeto'][$week['week']] = $week['uberNeto'];
			$weeklyData['boltNeto'][$week['week']] = $week['boltNeto'];
			$weeklyData['ukupnoNeto'][$week['week']] = $week['ukupnoNetoSvi'];
			$weeklyData['gotovina'][$week['week']] = $week['ukupnoGotovinaSvi'];
			$weeklyData['zaradaFirme'][$week['week']] = $week['zaradaFirme'];
			$weeklyData['week'][$week['week']] = $week['week'];
		}
		return $weeklyData;
	}
	
	public function posaljiPoruku(){
		$vozaciModel = new DriverModel();
		$session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$contacts = $vozaciModel->select('vozac, mobitel')->where('fleet', $fleet)->where('aktivan', 1)->get()->getResultArray();
		$data['contacts'] = $contacts;
		$data['page'] = 'Posalji whatsApp poruku';
		$data['role'] = $role;
		$data['fleet'] = $fleet;
		return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/posaljiPoruku')		
			. view('footer');
		
	}
	
	public function sendmsg(){
		$session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$vozaciModel = new DriverModel();
		$UltramsgLib = new UltramsgLib();
		
		$contacts = $vozaciModel->select('vozac, mobitel')->where('fleet', $fleet)->where('aktivan', 1)->get()->getResultArray();
		$vozac = $this->request->getVar('vozac');
		$msg = $this->request->getVar('poruka');
		$to = $vozaciModel->select('mobitel')->where('vozac', $vozac)->get()->getRow();
		$to = (string)$to->mobitel;
		$poruka['to'] = $to;
		$poruka['msg'] = $msg;
		$poruka = $UltramsgLib->sendMsg($poruka);
		if($poruka['status'] == 'success'){
			$session->setFlashdata('msgPoruka', "Uspješno poslana poruka \"$msg\" vozaču/vozačici $vozac.");
			session()->setFlashdata('alert-class', 'alert-success');
		}
		else{
			$session->setFlashdata('msgPoruka', "Poruka \"$msg\" nije uspješno poslana vozaču/vozačici $vozac. Jer $to nije aktivan WhatsApp broj.");
			session()->setFlashdata('alert-class', 'alert-danger');
		}
		$data['poruka'] = $poruka;
		$data['contacts'] = $contacts;
		$data['page'] = 'Posalji whatsApp poruku';
		$data['role'] = $role;
		$data['fleet'] = $fleet;
		return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/posaljiPoruku')		
			. view('footer');
	}
	
	public function dugovi(){
		$dugoviModel = new DugoviModel();
		$driverModel = new DriverModel();
 		$doprinosiModel = new DoprinosiModel();
       $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$data['page'] = 'Dugovi';
		$data['fleet'] = $fleet;
		$week = $dugoviModel->where('fleet', $fleet)->get()->getLastRow();
		$week = $week->week;
		$dugovi = $dugoviModel->where('fleet', $fleet)->where('week', $week)->where('placeno', FALSE)->get()->getResultArray();
		
		foreach($dugovi as &$dug){
			$vozac = $driverModel->select('dob, broj_sati')->where('id', $dug['vozac_id'])->get()->getRowArray();
			$sati = $vozac['broj_sati'];
			$sati = floatval($sati);
			$dobString = $vozac['dob'];
			$doprinosi = $doprinosiModel->like('broj_sati', $sati)->get()->getRowArray();
			$dob = \DateTime::createFromFormat('m/d/Y', $dobString);

			// Get the current date
			$currentDate = new \DateTime();

			// Calculate the difference between the current date and date of birth
			$age = $currentDate->diff($dob)->y;		
			if ($doprinosi === null) {
					// Error handling or printing the contents of $vozac array
$query = $doprinosiModel->getLastQuery();
echo $query;					
				echo "Error: Doprinosi is null for Vozac: ";
					var_dump($sati);
				$dug['netoPlaca'] = '1000';
					continue; // Continue to the next iteration of the loop
				}
			if($age < 30){
				$netoPlaca = $doprinosi['bruto_do_30'] - $doprinosi['dop_do_30'];
			}else{
				$netoPlaca = $doprinosi['bruto_od_30'] - $doprinosi['dop_od_30'];
			}
			$dug['netoPlaca'] = $netoPlaca;
			
		}
		
		$data['dugovi'] = $dugovi;
        return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/dugovi')
			. view('footer');
	}
	
	public function kreirajUskratu($id = null){
		$dugoviModel = new DugoviModel();
		$driverModel = new DriverModel();
		$tvrtkaModel = new TvrtkaModel();
		$FlotaModel = new FlotaModel();
		$doprinosiModel = new DoprinosiModel();
        $session = session();
		$fleet = $session->get('fleet');
		$user = $session->get('name');
		$fleet_id = $session->get('fleet_id');
		
		$dug = $dugoviModel->where('id', $id)->get()->getRowArray();
		$driver = $driverModel->where('id', $dug['vozac_id'])->get()->getRowArray();
		$doprinosi = $doprinosiModel->where('broj_sati', $driver['broj_sati'])->get()->getRowArray();
		$flota = $FlotaModel->where('id', $fleet_id)->get()->getRowArray();
		$tvrtka = $tvrtkaModel->where('id', $flota['tvrtka_id'])->get()->getRowArray();
		
		$dobString = $driver['dob'];
        $dob = \DateTime::createFromFormat('m/d/Y', $dobString);

        // Get the current date
        $currentDate = new \DateTime();

        // Calculate the difference between the current date and date of birth
        $age = $currentDate->diff($dob)->y;		
		
		if($age < 30){
			$netoPlaca = $doprinosi['bruto_do_30'] - $doprinosi['dop_do_30'];
		}else{
			$netoPlaca = $doprinosi['bruto_od_30'] - $doprinosi['dop_od_30'];
		}
		$placaDugRazlika = $dug['iznos'] + $netoPlaca;
		
		if($placaDugRazlika > 0){
			$uskraceno = $dug['iznos'];
		}else{
			$uskraceno = $netoPlaca;
		}
		
		$data['role'] = $session->get('role');
		$data['fleet'] = $fleet;
		$data['page'] = 'Kreiraj uskratu duga';
		$data['placaDugRazlika'] = $placaDugRazlika;
		$data['dug'] = $dug;
		$data['user'] = $user;
		$data['uskraceno'] = $uskraceno;
		$data['driver'] = $driver;
		$data['netoPlaca'] = $netoPlaca;
		$data['tvrtka'] = $tvrtka;
		
		
        return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/kreirajUskratu')
			. view('footer');
		
	}
	
	public function uskrataSave(){
		$months = array(
			1 => 'Siječanj',
			2 => 'Veljača',
			3 => 'Ožujak',
			4 => 'Travanj',
			5 => 'Svibanj',
			6 => 'Lipanj',
			7 => 'Srpanj',
			8 => 'Kolovoz',
			9 => 'Rujan',
			10 => 'Listopad',
			11 => 'Studeni',
			12 => 'Prosinac'
		);
		$tvrtkaModel = new TvrtkaModel();
		$napomeneModel = new NapomeneModel();
		$driverModel = new DriverModel();
		
		$session = session();
		$dugoviModel = new DugoviModel();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$postData = $this->request->getPost();
		$tvrtka = $tvrtkaModel->where('id', $postData['tvrtka_id'])->get()->getRowArray();
		$driver = $driverModel->where('id', $postData['vozac_id'])->get()->getRowArray();
		$id = $postData['dug_id'];
		$mjesec = $postData['mjesec'];
		$mjesec = $months[$mjesec];
		$currentDateTime = new \DateTime();

		// Format the date
		$formattedDate = $currentDateTime->format('d.m.Y');
		
		
		$napomena = array();
		$razlika = $postData['dug_iznos'] + $postData['netoPlaca'];

		$data['postData'] = $postData;
		$data['fleet'] = $fleet;
		$data['page'] = 'Uskrata na plaći ' .$postData['vozac'];
		$data['role'] = $role;
		$data['datum'] = $formattedDate;
		$data['driver'] = $driver;
		$data['mjesec'] = $mjesec;
		$data['tvrtka'] = $tvrtka;
		$data['svrha'] = 'uskrataNaPlaci';
		
//						return view('adminDashboard/header', $data)
//							. view('adminDashboard/navBar')
//							. view('adminDashboard/uskrata')
//							. view('footer');
		if($razlika < 0){
			$dugPlacenCijeli = false;
			$dug['iznos'] = $razlika;
			$text = null;
			if($dugoviModel->update($id, $dug)){
				$text = 'Vozaču '.$postData['vozac'] .' je kreirana uskrata na plaći za mjesec ' .$mjesec .' u iznosu od '. $postData['netoPlaca'] .'€ te mu je ostalo još ' .$razlika .'€ za uplatiti. ';
				$napomena = array(
					'user' => $postData['user'],
					'driver_id' => $postData['vozac_id'],
					'napomena' => $text,
					'fleet' => $fleet
				);
					if($napomeneModel->insert($napomena)){
						session()->setFlashdata('dugPlacen', $text .' Sve je spremljeno u Bazu!');
						session()->setFlashdata('alert-class', 'alert-success');

						return view('adminDashboard/header', $data)
							. view('adminDashboard/navBar')
							. view('adminDashboard/uskrata')
							. view('footer');
					}else{
						session()->setFlashdata('dugPlacen', $text .'Došlo je do pogreške prilikom spremanja u bazu. EVIDENCIJU voditi te NAPOMENU upisati ručno.');
						session()->setFlashdata('alert-class', 'alert-warning');

						return view('adminDashboard/header', $data)
							. view('adminDashboard/navBar')
							. view('adminDashboard/uskrata')
							. view('footer');
								}
			}else{
				session()->setFlashdata('dugPlacen', 'Došlo je do pogreške pokušajte ponovo');
				session()->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to('/index.php/dugovi');
				
			}
			
			
			
		}else{
			$dugPlacenCijeli = true;
			$dug['iznos'] = 0;
			$dug['placeno'] = 1;
			$text = null;
			if($dugoviModel->update($id, $dug)){
				$text = 'Vozaču '.$postData['vozac'] .' je kreirana uskrata na plaći za mjesec ' .$mjesec .' u iznosu od '. $postData['dug_iznos'] .'€ te mu je ostalo još ' .$razlika .'€ za isplatiti na plaći. ';
				$napomena = array(
					'user' => $postData['user'],
					'driver_id' => $postData['vozac_id'],
					'napomena' => $text,
					'fleet' => $fleet
				);
					if($napomeneModel->insert($napomena)){
						session()->setFlashdata('dugPlacen', $text .' Sve je spremljeno u Bazu!');
						session()->setFlashdata('alert-class', 'alert-success');

						return view('adminDashboard/header', $data)
							. view('adminDashboard/navBar')
							. view('adminDashboard/uskrata')
							. view('footer');
					}else{
						session()->setFlashdata('dugPlacen', $text .'Došlo je do pogreške prilikom spremanja u bazu. EVIDENCIJU voditi te NAPOMENU upisati ručno.');
						session()->setFlashdata('alert-class', 'alert-warning');

						return view('adminDashboard/header', $data)
							. view('adminDashboard/navBar')
							. view('adminDashboard/uskrata')
							. view('footer');
								}
			}else{
				session()->setFlashdata('dugPlacen', 'Došlo je do pogreške pokušajte ponovo');
				session()->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to('/index.php/dugovi');
				
			}
			
						
			
		}
		
		print_r($postData);
		echo $razlika;
		
	}
	
	public function dug($id = null){
		$session = session();
		$dugoviModel = new DugoviModel();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$dug = $dugoviModel->where('id', $id)->get()->getRowArray();
		$vozac_id = $dug['vozac_id'];
		$vozac = $dug['vozac'];
		$data['fleet'] = $fleet;
		$data['page'] = 'Dug od ' .$vozac;
		$data['role'] = $role;
		$data['dug'] = $dug;
        return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/dug')
			. view('footer');
	}
	
	public function tablicaDugova(){
		$session = session();
		$dugoviModel = new DugoviModel();
		$vozaciModel = new DriverModel();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		
		$last5Weeks = $this->getLast5WeeksExcludingCurrent();
		$drivers = $vozaciModel->select('id, vozac')->where('fleet', $fleet)->get()->getResultArray();
		$tablica = array();
		 foreach ($drivers as $driver) {
				$vozac_id = $driver['id'];
				$vozac = $driver['vozac'];

				$driverData = array(
					'vozac_id' => $vozac_id,
					'vozac' => $vozac
				);
			 $ukupno = 0;
        foreach ($last5Weeks as $weekIdentifier) {
            $dugoviData = $dugoviModel->where('vozac_id', $vozac_id)
                ->where('fleet', $fleet)
                ->where('week', $weekIdentifier)
                ->where('placeno', 0)
                ->first();
            $iznos = $dugoviData ? $dugoviData['iznos'] : 0;

            $driverData['week_' . $weekIdentifier] = $iznos;
            //$driverData['placeno_week_' . $weekIdentifier] = $dugoviData['placeno'];
			
			
			$ukupno += $iznos;
            // Check if iznos is non-zero for any week
        }

        // Add the driverData to tablica only if there is a non-zero iznos
        if ($ukupno != 0) {
            $tablica[] = $driverData;
        }
    }		
		
		
		$data['last5Weeks'] = $last5Weeks;
		$data['page'] = 'Tablica dugova';
		$data['drivers'] = $tablica; 
		$data['fleet'] = $fleet;
		$data['role'] = $role;
        return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/tablicaDugova')
			. view('footer');
	}
	
	public function spremiDug(){
		$session = session();
		$dugoviModel = new DugoviModel();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$id = $this->request->getVar('id');
		$data = [
			'id'					=> $this->request->getVar('id'),
			'iznos'					=> $this->request->getVar('iznos'), 
			];
		
		if($dugoviModel->update($id, $data)){
            session()->setFlashdata('dugPlacen', 'Dug je uspješno editiran.');
            session()->setFlashdata('alert-class', 'alert-success');
			return redirect()->to('/index.php/dugovi');
		}else{
            session()->setFlashdata('dugPlacen', 'Promjena nije spremljena.');
            session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to('/index.php/dug/'). '/' .$dug['id'];
		}
		
	}
	
	public function dugPlacen($id = null){
	 date_default_timezone_set('Europe/Zagreb');
		$time = time();
         $timestamp = date('Y-m-d H:i:s', $time);
		$session = session();
		$fleet = $session->get('fleet');
		$user = $session->get('name');
		$user_id = $session->get('id');
		$dugoviModel = new DugoviModel();
		$dugoviNaplataModel = new DugoviNaplataModel();
		$napomeneModel = new NapomeneModel();
		$dug = $dugoviModel->where('id', $id)->get()->getRowArray();
		$dug['placeno'] = 1;
		if($dugoviModel->update($id, $dug)){
			$data = array(
				'user' => $user,
				'user_id' => $user_id,
				'iznos' => $dug['iznos'],
				'vozac' => $dug['vozac'],
				'fleet' => $fleet,
				'predano' => 'DA',
				'primljeno' => 'DA',
				'dug_id' => $id,
				'timestamp' => $timestamp,
				'nacin_placanja' => 'Aircash',
			);
			if($dugoviNaplataModel->save($data)){
				$session->setFlashdata('dugPlacen', ' ' .$dug['vozac'].' je platio svoj dug u iznosu od ' .$dug['iznos'] .' € putem Aircash-a');
				session()->setFlashdata('alert-class', 'alert-success');
				return redirect()->to('/index.php/dugovi');
			}else{
				$session->setFlashdata('dugPlacen', ' ' .$dug['vozac'].' je platio svoj dug u iznosu od ' .$dug['iznos'] .' € putem Aircash-a i obrisan mu je dug ali nije spremljena naplata');
				session()->setFlashdata('alert-class', 'alert-warrning');
				return redirect()->to('/index.php/dugovi');
			}
		}
		else{
			$session->setFlashdata('dugPlacen', 'Nismo uspjeli označiti dug '.$dug['vozac'].' kao plaćen');
			session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to('/index.php/dugovi');
		}
	}
	
	public function dugPlacenPoslovnica($id = null){
	 date_default_timezone_set('Europe/Zagreb');
		$time = time();
         $timestamp = date('Y-m-d H:i:s', $time);
		$session = session();
		$fleet = $session->get('fleet');
		$user = $session->get('name');
		$user_id = $session->get('id');
		$dugoviModel = new DugoviModel();
		$dugoviNaplataModel = new DugoviNaplataModel();
		$napomeneModel = new NapomeneModel();
		$dug = $dugoviModel->where('id', $id)->get()->getRowArray();
		$dug['placeno'] = 1;
		if($dugoviModel->update($id, $dug)){
			$data = array(
				'user' => $user,
				'user_id' => $user_id,
				'iznos' => $dug['iznos'] * -1,
				'vozac' => $dug['vozac'],
				'fleet' => $fleet,
				'dug' => $id,
				'timestamp' => $timestamp,
				'nacin_placanja' => 'Gotovina',
			);
			if($dugoviNaplataModel->save($data)){
				$session->setFlashdata('dugPlacen', ' ' .$dug['vozac'].' je platio svoj dug u iznosu od ' .$dug['iznos'] .' € u Poslovnici');
				session()->setFlashdata('alert-class', 'alert-success');
				$this->potvrdaOPlacanju($data);
				//return redirect()->to('/index.php/dugovi');
			}else{
				$session->setFlashdata('dugPlacen', ' ' .$dug['vozac'].' je platio svoj dug u iznosu od ' .$dug['iznos'] .' € u Poslovnici i obrisan mu je dug ali nije spremljena naplata');
				session()->setFlashdata('alert-class', 'alert-warrning');
				$this->potvrdaOPlacanju($data);
				
				return redirect()->to('/index.php/dugovi');
			}
		}
		else{
			$session->setFlashdata('dugPlacen', 'Nismo uspjeli označiti dug '.$dug['vozac'].' kao plaćen');
			session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to('/index.php/dugovi');
		}
	}	
	
	private function potvrdaOPlacanju($data){
		$FlotaModel = new FlotaModel();
		$TvrtkaModel = new TvrtkaModel();
		$driverModel = new DriverModel();
		$timestamp = $data['timestamp'];
		// Create a DateTime object from the timestamp
		$date = new \DateTime($timestamp);

		// Format the date to "D.M.Y" with leading zeros
		$datum = $date->format("d.m.Y");
		$fleet = $data['fleet'];
		$postavkeFlote = $FlotaModel->where('naziv', $fleet)->get()->getRowArray();
		$tvrtka_id = $postavkeFlote['tvrtka_id'];
		$tvrtka = $TvrtkaModel->where('id', $tvrtka_id)->get()->getRowArray();
		$driver = $driverModel->where('vozac', $data['vozac'])->get()->getRowArray();
		$data['driver'] = $driver;
		$data['datum'] = $datum;
		$data['tvrtka'] = $tvrtka;
		    $dompdf = new Dompdf();
		$options = $dompdf->getOptions();
		$options->setDefaultFont('DejaVu Sans'); // This font supports a broader range of characters
		$dompdf->setOptions($options);

		// Generate the PDF content
		$html = view('adminDashboard/potvrdaoPlacanju', $data);
		$dompdf->loadHtml($html, 'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		// Output the PDF to the client
		$filename = 'potvrda_o_uplati_gotovine_' . $driver['vozac'] . '.pdf';
		header('Content-Type: application/pdf');
		header("Content-Disposition: attachment; filename=\"$filename\"");
		echo $dompdf->output();	

		
	}
public function napomenaSave()
{
	 date_default_timezone_set('Europe/Zagreb');
    $session = session();
	$fleet = $session->get('fleet');
    $napomeneModel = new NapomeneModel();
    $data = $this->request->getPost();

    if (!empty($data)) {
		$time = time();
         $timestamp = date('Y-m-d H:i:s', $time);
        $user = $data['user'];
        $driver_id = $data['driver_id'];
        $napomena = $data['napomena'];

        $saveData = [
            'timestamp' => $timestamp,
            'user' => $user,
            'driver_id' => $driver_id,
            'napomena' => $napomena,
            'fleet' => $fleet,
        ];
		
//		var_dump($saveData);
//		die();
        if ($napomeneModel->save($saveData)) {
            $session->setFlashdata('msgNapomena', 'Napomena je uspješno dodana');
            session()->setFlashdata('alert-class', 'alert-success');
            return redirect()->to("/index.php/drivers/$driver_id");
        } else {
            $session->setFlashdata('msgNapomena', 'Došlo je do pogreške, napomena nije dodana');
            session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to("/index.php/drivers/$driver_id");
        }
    } else {
        $session->setFlashdata('msgNapomena', 'Došlo je do pogreške, nije bilo napomene');
        session()->setFlashdata('alert-class', 'alert-danger');
        return redirect()->toPrevious();
    }
}	
	
	public function uberImport()
	{
		
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$data['page'] = 'Report Import';
		$data['fleet'] = $fleet;
        return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
		. view('adminDashboard/importUber')
			. view('footer');
		
	}
	
	
	public function uberReportImport()
	{
//		var_dump($_FILES);
        $session = session();
		$fleet = $session->get('fleet');
		$data = $session->get();
		 $input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,10240]|ext_in[file,csv],'
        ]);
		
		
			if ($this->request->getMethod() === 'post') {
				// Get the uploaded file
				$file = $this->request->getFile('file');

				if ($file !== null) {
					if ($file->getError() === UPLOAD_ERR_OK) {
						// File upload was successful
						echo 'File uploaded successfully';
						//var_dump($file);
					} else {
						// Handle the upload error
						echo 'File upload failed. Error code: ' . $file->getError();
					}
				} else {
					echo 'No file posted 1';
				}
			} else {
				echo 'No file posted 2';
			}		
		
		if (!$input) {
 			$data['page'] = 'Report Import';
           $data['validation'] = $this->validator;
			return view('adminDashboard/header', $data)
 			. view('adminDashboard/navBar')
           . view('adminDashboard/importUber', $data)
				. view('footer'); 
        }else{
            if($file = $this->request->getFile('file')) {
            if ($file->isValid() && ! $file->hasMoved()) {
				$original_name = $file->getName();
                $newName = $file->getRandomName();
				
				$year = substr($original_name,0,4);
				$month = substr($original_name,4,2);
				$day = substr($original_name,6,2);
				$date = $year.'-'.$month.'-'.$day;
				$week = date("W", strtotime($date));
				$week = $year. $week;
				
				
                $file->move('../public/csvfile', $newName);
                $file = fopen("../public/csvfile/".$newName,"r");
				 //Map lines of the string returned by file function to $rows array.
				$rows   = array_map('str_getcsv', file("../public/csvfile/".$newName));
				//Get the first row that is the HEADER row.
				$header_row = array_shift($rows);
				$header_row = str_replace(": ", "", $header_row);
				$header_row = str_replace("č", "c", $header_row);
				$header_row = str_replace("ć", "c", $header_row);
				$header_row = str_replace("š", "s", $header_row);
				$header_row = str_replace("ž", "z", $header_row);
				$header_row = str_replace("đ", "d", $header_row);
				$header_row = str_replace(" ", "_", $header_row);
				$header_row = str_replace(":", "_", $header_row);
				//This array holds the final response.
				$uberReport    = array();
				$count = 0;
				foreach($rows as $row) {
					if(!empty($row)){
						$uberReport[] = array_combine($header_row, $row);
						$count++;
					}
				}
                    $driverData = new UberReportModel();
                    $findRecord = $driverData->where('report_for_week', $week)->where('fleet', $fleet)->countAllResults();

				$count1 = 0;
				foreach($uberReport as $driver){
					$driver['UUID_vozaca'] = $driver[array_key_first($driver)];
					$driver['report_for_week'] = $week;
					$driver['Vozac'] = $driver['Vozacevo_ime']. ' '. $driver['Vozacevo_prezime'];
					$driver['fleet'] = $fleet;
					
                    if($findRecord == 0){
                        if($driverData->insert($driver)){
                            $count1++;}
					}
				}


                session()->setFlashdata('message_uber', $count.' rows successfully loaded,</br> '.$count1. ' rows unique and inserted');
                session()->setFlashdata('alert-class', 'alert-success');
            }
            else{
                session()->setFlashdata('message_uber', 'CSV file coud not be imported.');
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            }else{
            session()->setFlashdata('message_uber', 'CSV file coud not be imported.');
            session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
 			$data['page'] = 'Report Import';
		$data['uberReport'] = $uberReport;
		$data['header_row'] = $header_row;
 		$data['fleet'] = $fleet;
       return view('adminDashboard/header', $data)
 			. view('adminDashboard/navBar')
       . view('adminDashboard/importUber',$data)
		   . view('footer');
		
		
	}
	
	
	public function boltReportImport()
	{
        $session = session();
		$fleet = $session->get('fleet');
		$data = $session->get();
		 $input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv],'
        ]);
        if (!$input) {
            $data['validation'] = $this->validator;
 		$data['fleet'] = $fleet;
 			$data['page'] = 'Report Import';
         return view('adminDashboard/header', $data)
 			. view('adminDashboard/navBar')
         . view('adminDashboard/importUber', $data)
			 . view('footer'); 
        }else{
            if($file = $this->request->getFile('file')) {
            if ($file->isValid() && ! $file->hasMoved()) {
				$original_name = $file->getName();
                $newName = $file->getRandomName();
				$year = substr($original_name,21,4);
				$week = substr($original_name,26,2);
				$week = $year. $week ;
				
				
                $file->move('../public/csvfile', $newName);
                $file = fopen("../public/csvfile/".$newName,"r");
				 //Map lines of the string returned by file function to $rows array.
				$rows   = array_map('str_getcsv', file("../public/csvfile/".$newName));
				
				$new_array = array();
				
				foreach($rows as $row){
					if($row[0] != null){
						$new_array[] = $row;
					}else{
						// exit loop and continue rest of the script
						break;
					}
				}
				
				//Get the first row that is the HEADER row.
				$header_row = array_shift($new_array);
				$header_row = str_replace('"', "", $header_row);
				$header_row = str_replace(": ", "", $header_row);
				$header_row = str_replace("č", "c", $header_row);
				$header_row = str_replace("ć", "c", $header_row);
				$header_row = str_replace("š", "s", $header_row);
				$header_row = str_replace("ž", "z", $header_row);
				$header_row = str_replace("đ", "d", $header_row);
				$header_row = str_replace(" ", "_", $header_row);
				$header_row = str_replace(":", "_", $header_row);
				$header_row = str_replace("(", "", $header_row);
				$header_row = str_replace(")", "", $header_row);
				//This array holds the final response.
				$boltReport    = array();
				$count = 0;
			foreach($new_array as $row) {
					if(!empty($row)){
						$boltReport[] = array_combine($header_row, $row);
					}
				}

				$boltReportTrimed = array();
				foreach($boltReport as $driver){
					if (!empty($driver['Telefonski_broj_vozaca'])){
						if($driver['Period'] != 'Period'){

						if(isset($driver['Utiliziranost']) AND !empty($driver['Utiliziranost'])){
								$driver['Utilization'] = $driver['Utiliziranost'];
								$driver['Vozac'] = $driver[array_key_first($driver)];
								if($driver['Vozac'] == 'Tomislav Miskovic'){
									$driver['Vozac'] = 'Tomislav Mišković';}
								$driver['report_for_week']= $week;
								$driver['fleet'] = $fleet;


								$boltReportTrimed[] = $driver;
								$count++;
								
								
							}
							if(isset($driver['Iskoristenje']) AND !empty($driver['Iskoristenje'])){
								
								$driver['Utilization'] = $driver['Iskoristenje'];
								$driver['Vozac'] = $driver[array_key_first($driver)];
								if($driver['Vozac'] == 'Tomislav Miskovic'){
									$driver['Vozac'] = 'Tomislav Mišković';}
								$driver['report_for_week']= $week;
								$driver['fleet'] = $fleet;


								$boltReportTrimed[] = $driver;
								$count++;
								
							}
						}
					}
					
				}


//				echo '<pre>';
//				print_r($boltReportTrimed);
//				die();
						$driverData = new BoltReportModel();
						$findRecord = $driverData->where('report_for_week', $week)->where('fleet', $fleet)->countAllResults();

				$count1 = 0;
				foreach($boltReportTrimed as $driver){
						if($findRecord == 0){
							
							if($driverData->insert($driver)){
								$count1++;}
				}
				}


                session()->setFlashdata('message_bolt', $count.' rows successfully loaded,</br> '.$count1. ' rows unique and inserted');
                session()->setFlashdata('alert-class', 'alert-success');
            }
            else{
                session()->setFlashdata('message_bolt', 'CSV file coud not be imported.');
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            }else{
            session()->setFlashdata('message_bolt', 'CSV file coud not be imported.');
            session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
		$data['uberReport'] = $boltReport;
		$data['header_row'] = $header_row;
 			$data['page'] = $fleet;
		$data['fleet'] = $fleet;
        return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
        . view('adminDashboard/importUber',$data)
			. view('footer');
		
		
	}
	

	
		public function myPosReportImport()
	{
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		 $input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv],'
        ]);
        if (!$input) {
            $data['validation'] = $this->validator;
 			$data['page'] = 'Report Import';
 		$data['fleet'] = $fleet;
        return view('adminDashboard/header', $data)
 			. view('adminDashboard/navBar')
          . view('adminDashboard/importUber')
			. view('footer'); 
        }else{
            if($file = $this->request->getFile('file')) {
            if ($file->isValid() && ! $file->hasMoved()) {
				$original_name = $file->getName();
                $newName = $file->getRandomName();
				
				
				
                $file->move('../public/csvfile', $newName);
                $file = fopen("../public/csvfile/".$newName,"r");
				 //Map lines of the string returned by file function to $rows array.
				$rows   = array_map('str_getcsv', file("../public/csvfile/".$newName));
				//Get the first row that is the HEADER row.
				$header_row = array_shift($rows);
				$header_row = str_replace(": ", "", $header_row);
				$header_row = str_replace("č", "c", $header_row);
				$header_row = str_replace("ć", "c", $header_row);
				$header_row = str_replace("š", "s", $header_row);
				$header_row = str_replace("ž", "z", $header_row);
				$header_row = str_replace("đ", "d", $header_row);
				$header_row = str_replace(" ", "_", $header_row);
				$header_row = str_replace(":", "_", $header_row);
				//This array holds the final response.
				$MyPosReport    = array();
				$count = 0;
				
//				echo '<pre>';
//				print_r($header_row);
//				echo '</pre></br>';
//				echo '<pre>';
//				print_r($rows);
//				echo '</pre>';
//				die();
				foreach($rows as $row) {
					if(!empty($row)){
						$MyPosReport[] = array_combine($header_row, $row);
						$count++;
					}
				}
				$year = substr($MyPosReport[0]['Date_initiated'],6,4);
				$month = substr($MyPosReport[0]['Date_initiated'],3,2);
				$day = substr($MyPosReport[0]['Date_initiated'],0,2);
				$date = $year.'-'.$month.'-'.$day;
				$week = date("W", strtotime($date));
				$week = $year. $week;

				$driverData = new MyPosReportModel();
                    $findRecord = $driverData->where('report_for_week', $week)->where('fleet', $fleet)->countAllResults();

				$count1 = 0;
				foreach($MyPosReport as $driver){
					$driver['Date_initiated'] = $driver[array_key_first($driver)];
					$driver['report_for_week'] = $week;
					$driver['fleet'] = $fleet;
					
                    if($findRecord == 0){
                        if($driverData->insert($driver)){
                            $count1++;}
				}
				}


                session()->setFlashdata('message_myPos', $count.' rows successfully loaded,</br> '.$count1. ' rows unique and inserted');
                session()->setFlashdata('alert-class', 'alert-success');
            }
            else{
                session()->setFlashdata('message_myPos', 'CSV file could not be imported.');
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            }else{
            session()->setFlashdata('message_myPos', 'CSV file could not be imported.');
            session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
		$data['MyPosReport'] = $MyPosReport;
		$data['fleet'] = $fleet;
		$data['header_row'] = $header_row;
 			$data['page'] = 'Report Import';
         return view('adminDashboard/header', $data)
 			. view('adminDashboard/navBar')
      . view('adminDashboard/importUber',$data)
			 . view('footer');
		
		
	}
	
	public function drivers()
	{
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$driversFind = new DriverModel();
		$drivers = $driversFind->select('id, vozac, email, mobitel, dob, uber, bolt, taximetar, myPos, refered_by, referal_reward, vrsta_provizije, iznos_provizije, popust_na_proviziju, prijava, broj_sati')->where('fleet', $fleet)->where('aktivan', '1')->get()->getResultArray();
		$data['page'] = 'Drivers';
		$data['drivers'] = $drivers;
		$data['fleet'] = $fleet;
		echo view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			.view('adminDashboard/drivers')
			. view('footer');
		
	}
	public function driversNeaktiv()
	{
        $session = session();
		$fleet = $session->get('fleet');
		$data = $session->get();
		$driversFind = new DriverModel();
		$drivers = $driversFind->select('id, vozac, email, mobitel, dob, uber, bolt, taximetar, myPos, refered_by, referal_reward, vrsta_provizije, iznos_provizije, popust_na_proviziju, prijava, broj_sati')->where('fleet', $fleet)->where('aktivan', '0')->get()->getResultArray();
		$data['page'] = 'Inactive Drivers';
		$data['drivers'] = $drivers;
		$data['fleet'] = $fleet;
		echo view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			.view('adminDashboard/neaktdrivers')
			. view('footer');
		
	}
	
	public function driver($id = null){
        $session = session();
		$fleet = $session->get('fleet');
		$driverData = new DriverModel();
		$UltramsgLib = new UltramsgLib();
		$flotaModel = new FlotaModel();
		$postavkeFlote = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$data = $session->get();
		$driverObracunModel = new ObracunModel();
		$driverObracun = $driverObracunModel->where('vozac_id', $id)->get()->getResultArray();
		$driver = $driverData->where('id', $id)->get()->getRowArray();
		if(!empty($driver['whatsApp'])){
			$mobitel = $driver['whatsApp'];
		}else{
			$mobitel = $driver['mobitel'];
		}
		if($postavkeFlote['imaWhatsApp'] != 1){
			$valjanost = 'nemamo WhatsApp API';
		}else{
			$valjanost = $UltramsgLib->checkContact($mobitel);
			$valjanost = $valjanost['status'];
		}
		
		$driversFind = new DriverModel();
		$drivers = $driversFind->select('vozac, id')->where('fleet', $fleet)->where('aktivan', '1')->get()->getResultArray();
		$vozilaModel = new VozilaModel();
		$vozilo = $vozilaModel->where('vozac_id', $id)->get()->getRowArray();
		$prijaveModel = new PrijaveModel();
		$prijave = $prijaveModel->select('prijave.*, users.name as administrator')
			->join('users', 'users.id = prijave.user_id', 'left')
			->where('prijave.vozac_id', $id)
			->orderBy('prijave.vozac_id', 'DESC')
			->get()
			->getResultArray();
		
		$napomeneModel = new NapomeneModel();
		$napomene = $napomeneModel->where('driver_id', $id)->get()->getResultArray();
		
		$prijaveCount = count(array_filter($prijave, 'is_array'));
		if($prijaveCount > 1){
			$aneks = TRUE;
		}else{
			$aneks = FALSE;
		}

		$data['aneks'] = $aneks;
		$data['prijave'] = $prijave;
		$data['napomene'] = $napomene;
		$data['valjanost'] = $valjanost;
		$data['vozilo'] = $vozilo;
		$data['driverId'] = $driver['id'];
		$data['drivers'] = $drivers;
		$data['driverObracun'] = $driverObracun;
		$data['driver'] = $driver;
		$data['fleet'] = $driver['fleet'];
		$data['page'] = $driver['vozac'];
		

        echo view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/driver')
			. view('footer');
		
	}
	
	
	public function radniOdnos($id = null){
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$driverData = new DriverModel();
		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();
		$driver = $driverData->where('id', $id)->get()->getRowArray();
		$flota = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
//		print_r($flota);
//		die();
		$tvrtka_id = $flota['tvrtka_id'];
		$tvrtka = $tvrtkaModel->where('id', $tvrtka_id)->get()->getRowArray();
		
		$pocetakRadaVozac = $driver['pocetak_prijave'];
		$pocetakRadaVozac = str_replace('-', '', $pocetakRadaVozac);
		$pocetakRadaTvrtka = $tvrtka['pocetak_tvrtke'];
		$pocetakRadaTvrtka = str_replace('-', '', $pocetakRadaTvrtka);

		// Get today's date in the same format as $pocetakRadaVozac and $pocetakRadaTvrtka
		$currentDate = date('Ymd');

		if ($currentDate > $pocetakRadaVozac && $currentDate > $pocetakRadaTvrtka) {
			$godina = substr($currentDate, 0, 4);
			$mjesec = substr($currentDate, 4, 2);
			$dan = substr($currentDate, 6, 2);
			$pocetakRada = $dan . '.' . $mjesec . '.' . $godina . '.';
		} else {
			if ($pocetakRadaTvrtka < $pocetakRadaVozac) {
				$godina = substr($pocetakRadaVozac, 0, 4);
				$mjesec = substr($pocetakRadaVozac, 4, 2);
				$dan = substr($pocetakRadaVozac, 6, 2);
				$pocetakRada = $dan . '.' . $mjesec . '.' . $godina . '.';
			} else {
				$godina = substr($pocetakRadaTvrtka, 0, 4);
				$mjesec = substr($pocetakRadaTvrtka, 4, 2);
				$dan = substr($pocetakRadaTvrtka, 6, 2);
				$pocetakRada = $dan . '.' . $mjesec . '.' . $godina . '.';
			}
		}
		$brutoPlaca = 840.00;
		if($driver['broj_sati'] == 8){
			$brutoPlaca = 840;
		}
		elseif($driver['broj_sati'] == 4){
			$brutoPlaca = $brutoPlaca /2 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 2){
			$brutoPlaca = $brutoPlaca /4 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 1.5){
			$brutoPlaca = $brutoPlaca /5.33 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 3.29){
			$brutoPlaca = $brutoPlaca /2.43 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		$tjednisati = $driver['broj_sati'] * 5;
		$data['radniOdnos'] = $driver['radniOdnos'];
		$data['driverId'] = $driver['id'];
		$data['brutoPlaca'] = $brutoPlaca;
		$data['tjedniSati'] = $tjednisati;
		$data['pocetakRada'] = $pocetakRada;
		$data['tvrtka'] = $tvrtka;
		$data['driver'] = $driver;
		$data['fleet'] = $driver['fleet'];
		$data['page'] = $driver['vozac'] .' - radni odnos';
		$data['svrha'] = 'UgovoroRadu';
		echo view('adminDashboard/header', $data)
			.view('adminDashboard/radniOdnos');
		
	}	
	
	public function radniOdnosBolt($id = null){
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$driverData = new DriverModel();
		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();
		$driver = $driverData->where('id', $id)->get()->getRowArray();
		$flota = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
//		print_r($flota);
//		die();
		$tvrtka_id = $flota['tvrtka_id'];
		$tvrtka = $tvrtkaModel->where('id', $tvrtka_id)->get()->getRowArray();
		
		$pocetakRadaVozac = $driver['pocetak_prijave'];
		$pocetakRadaVozac = str_replace('-', '', $pocetakRadaVozac);
		$pocetakRadaTvrtka = $tvrtka['pocetak_tvrtke'];
		$pocetakRadaTvrtka = str_replace('-', '', $pocetakRadaTvrtka);

		// Get today's date in the same format as $pocetakRadaVozac and $pocetakRadaTvrtka
		$currentDate = date('Ymd');

		if ($currentDate > $pocetakRadaVozac && $currentDate > $pocetakRadaTvrtka) {
			$godina = substr($currentDate, 0, 4);
			$mjesec = substr($currentDate, 4, 2);
			$dan = substr($currentDate, 6, 2);
			$pocetakRada = $dan . '.' . $mjesec . '.' . $godina . '.';
		} else {
			if ($pocetakRadaTvrtka < $pocetakRadaVozac) {
				$godina = substr($pocetakRadaVozac, 0, 4);
				$mjesec = substr($pocetakRadaVozac, 4, 2);
				$dan = substr($pocetakRadaVozac, 6, 2);
				$pocetakRada = $dan . '.' . $mjesec . '.' . $godina . '.';
			} else {
				$godina = substr($pocetakRadaTvrtka, 0, 4);
				$mjesec = substr($pocetakRadaTvrtka, 4, 2);
				$dan = substr($pocetakRadaTvrtka, 6, 2);
				$pocetakRada = $dan . '.' . $mjesec . '.' . $godina . '.';
			}
		}
		$brutoPlaca = 700.00;
		if($driver['broj_sati'] == 8){
			$brutoPlaca = 700;
		}
		elseif($driver['broj_sati'] == 4){
			$brutoPlaca = $brutoPlaca /2 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 2){
			$brutoPlaca = $brutoPlaca /4 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 1.5){
			$brutoPlaca = $brutoPlaca /5.33 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		$tjednisati = $driver['broj_sati'] * 5;
		$data['radniOdnos'] = $driver['radniOdnos'];
		$data['driverId'] = $driver['id'];
		$data['brutoPlaca'] = $brutoPlaca;
		$data['tjedniSati'] = $tjednisati;
		$data['pocetakRada'] = $pocetakRada;
		$data['tvrtka'] = $tvrtka;
		$data['driver'] = $driver;
		$data['fleet'] = $driver['fleet'];
		$data['page'] = $driver['vozac'] .' - radni odnos Bolt';
		$data['svrha'] = 'UgovoroRadu';
		echo view('adminDashboard/header', $data)
			.view('adminDashboard/radniOdnosBolt');
		
	}
	
	
	public function ugovoroRadu($id = null){
		
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$driverData = new DriverModel();
		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();
		$driver = $driverData->where('id', $id)->get()->getRowArray();
		$flota = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
//		print_r($flota);
//		die();
		$tvrtka_id = $flota['tvrtka_id'];
		$tvrtka = $tvrtkaModel->where('id', $tvrtka_id)->get()->getRowArray();
		$pocetakRadaVozac = $driver['pocetak_prijave'];
		$pocetakRadaVozac = str_replace('-', '', $pocetakRadaVozac);
		$pocetakRadaTvrtka = $tvrtka['pocetak_tvrtke'];
		$pocetakRadaTvrtka = str_replace('-', '', $pocetakRadaTvrtka);
		if($pocetakRadaTvrtka < $pocetakRadaVozac){
			$godina = substr($pocetakRadaVozac,0,4) ;
			$mjesec = substr($pocetakRadaVozac,4,2) ;
			$dan = substr($pocetakRadaVozac,6,2) ;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
		}
		else{
			$godina = substr($pocetakRadaTvrtka,0,4) ;
			$mjesec = substr($pocetakRadaTvrtka,4,2) ;
			$dan = substr($pocetakRadaTvrtka,6,2) ;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
		}
		$brutoPlaca = 840.00;
		if($driver['broj_sati'] == 8){
			$brutoPlaca = 840;
		}
		elseif($driver['broj_sati'] == 6){
			$brutoPlaca = $brutoPlaca /4 * 3 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 4){
			$brutoPlaca = $brutoPlaca /2 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 2){
			$brutoPlaca = $brutoPlaca /4 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 1.5){
			$brutoPlaca = $brutoPlaca /5.33 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 3.29){
			$brutoPlaca = $brutoPlaca /8 * 3.29 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		
		if($driver['broj_sati'] == 3.29){
			$tjednisati = 17.41;
		}else{
		$tjednisati = $driver['broj_sati'] * 5;
		}
		$data['driverId'] = $driver['id'];
		$data['brutoPlaca'] = $brutoPlaca;
		$data['tjedniSati'] = $tjednisati;
		$data['pocetakRada'] = $pocetakRada;
		$data['tvrtka'] = $tvrtka;
		$data['driver'] = $driver;
		$data['fleet'] = $driver['fleet'];
		$data['page'] = $driver['vozac'];
		$data['svrha'] = 'UgovoroRadu';
		echo view('adminDashboard/header', $data)
			.view('adminDashboard/ugovoroRadu');
		
	}
	
	public function ugovoroRaduPdf($id = null) {
		helper('dompdf');
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$driverData = new DriverModel();
		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();
		$driver = $driverData->where('id', $id)->get()->getRowArray();
		$flota = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$tvrtka_id = $flota['tvrtka_id'];
		$tvrtka = $tvrtkaModel->where('id', $tvrtka_id)->get()->getRowArray();
		$pocetakRadaVozac = $driver['pocetak_prijave'];
		$pocetakRadaVozac = str_replace('-', '', $pocetakRadaVozac);
		$krajRadaVozac = $driver['kraj_prijave'];
		$krajRadaVozac = str_replace('-', '', $krajRadaVozac);
		$pocetakRadaTvrtka = $tvrtka['pocetak_tvrtke'];
		$pocetakRadaTvrtka = str_replace('-', '', $pocetakRadaTvrtka);
		
		if($driver['kraj_prijave']){
			$godina = substr($krajRadaVozac,0,4) ;
			$mjesec = substr($krajRadaVozac,4,2) ;
			$dan = substr($krajRadaVozac,6,2) ;
			$krajRada = $dan.'.'.$mjesec.'.'.$godina.'.';
		}
		
		if($pocetakRadaTvrtka < $pocetakRadaVozac){
			$godina = substr($pocetakRadaVozac,0,4) ;
			$mjesec = substr($pocetakRadaVozac,4,2) ;
			$dan = substr($pocetakRadaVozac,6,2) ;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
		}
		else{
			$godina = substr($pocetakRadaTvrtka,0,4) ;
			$mjesec = substr($pocetakRadaTvrtka,4,2) ;
			$dan = substr($pocetakRadaTvrtka,6,2) ;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
		}
		$brutoPlaca = 840.00;
		if($driver['broj_sati'] == 8){
			$brutoPlaca = 840;
		}
		elseif($driver['broj_sati'] == 6){
			$brutoPlaca = $brutoPlaca /4 * 3 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 4){
			$brutoPlaca = $brutoPlaca /2 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 2){
			$brutoPlaca = $brutoPlaca /4 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 1.5){
			$brutoPlaca = $brutoPlaca /5.33 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 3.29){
			$brutoPlaca = $brutoPlaca /8 * 3.29 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		
		if($driver['broj_sati'] == 3.29){
			$tjednisati = 17.41;
		}else{
		$tjednisati = $driver['broj_sati'] * 5;
		}
		header('Content-Type: text/html; charset=UTF-8');
		$data['driverId'] = $driver['id'];
		$data['brutoPlaca'] = $brutoPlaca;
		$data['tjedniSati'] = $tjednisati;
		$data['pocetakRada'] = $pocetakRada;
		$data['krajRada'] = $krajRada;
		$data['tvrtka'] = $tvrtka;
		$data['driver'] = $driver;
		$data['fleet'] = $driver['fleet'];
		$data['page'] = $driver['vozac'];
		$data['svrha'] = 'UgovoroRadu';
 // Initialize Dompdf with UTF-8 support
    $dompdf = new Dompdf();
    $options = $dompdf->getOptions();
    $options->setDefaultFont('DejaVu Sans'); // This font supports a broader range of characters
    $dompdf->setOptions($options);

    // Generate the PDF content
    $html = view('adminDashboard/ugovoroRaduPrint', $data);
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output the PDF to the client
    $filename = 'ugovoro_radu_' . $driver['vozac'] . '.pdf';
    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    echo $dompdf->output();		
		
		
		
		
		
		
		
		
//		$dompdf = new Dompdf();
//		$dompdf -> set_option('defaultFontEncoding', 'UTF-8');
//		$options = $dompdf->getOptions();
//		$options->setDefaultFont('Courier');
//		//$options->setDefaultEncoding('UTF-8');
//		$dompdf->setOptions($options);
//		//$dompdf->set_option('defaultFont', 'Courier');
//    // Load DOMPDF library
//    //$this->load->library('dompdf');
//    // Load the view and generate HTML content
//    $html = view('adminDashboard/ugovoroRaduPrint', $data);
//
//    // Create a new DOMPDF instance
//    //$dompdf = new DOMPDF();
//
//    // Load the HTML content into the DOMPDF instance
//    $dompdf->loadHTML($html, 'UTF-8');
//
//    // Set page size and orientation
//    $dompdf->setPaper('A4', 'portrait');
//
//    // Render the HTML content to PDF
//    $dompdf->render();
//
//    // Output the PDF content as a file
//    $output = $dompdf->output();
//    $filename = 'ugovoro_radu_' . $driver['vozac'] . '.pdf';
//
//    // Send PDF headers and output content
//    header('Content-Type: application/pdf');
//    header("Content-Disposition: attachment; filename=\"$filename\"");
//    echo $output;
}
	public function aneksUgovoraPdf($id = null){
		helper('dompdf');
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$driverData = new DriverModel();
		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();
		$driver = $driverData->where('id', $id)->get()->getRowArray();
		$flota = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
//		print_r($flota);
//		die();
		$tvrtka_id = $flota['tvrtka_id'];
		$tvrtka = $tvrtkaModel->where('id', $tvrtka_id)->get()->getRowArray();
		$pocetakRadaVozac = $driver['pocetak_prijave'];
		$pocetakRadaVozac = str_replace('-', '', $pocetakRadaVozac);
		$pocetakRadaTvrtka = $tvrtka['pocetak_tvrtke'];
		$pocetakRadaTvrtka = str_replace('-', '', $pocetakRadaTvrtka);
		$pocetakPromjene = $driver['pocetak_promjene'];
		$pocetakPromjene = str_replace('-', '', $pocetakPromjene);
		$pocetakPromjene1 = str_replace('-', '', $pocetakPromjene);
		
			$godina = substr($pocetakPromjene,0,4) ;
			$mjesec = substr($pocetakPromjene,4,2) ;
			$dan = substr($pocetakPromjene,6,2) ;
			$pocetakPromjene = $dan.'.'.$mjesec.'.'.$godina.'.';

		
		if($pocetakRadaTvrtka < $pocetakRadaVozac){
			$godina = substr($pocetakRadaVozac,0,4) ;
			$mjesec = substr($pocetakRadaVozac,4,2) ;
			$dan = substr($pocetakRadaVozac,6,2) ;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
			$pocetakRada1 = $godina.$mjesec.$dan;
		}
		else{
			$godina = substr($pocetakRadaTvrtka,0,4) ;
			$mjesec = substr($pocetakRadaTvrtka,4,2) ;
			$dan = substr($pocetakRadaTvrtka,6,2) ;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
			$pocetakRada1 = $godina.$mjesec.$dan;
		}
		$brutoPlaca = 840.00;
		if($driver['broj_sati'] == 8){
			$brutoPlaca = 840;
		}
		elseif($driver['broj_sati'] == 6){
			$brutoPlaca = $brutoPlaca /4 * 3 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 4){
			$brutoPlaca = $brutoPlaca /2 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 2){
			$brutoPlaca = $brutoPlaca /4 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 1.5){
			$brutoPlaca = $brutoPlaca /5.33 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 3.29){
			$brutoPlaca = $brutoPlaca /8 * 3.29 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		if($driver['broj_sati'] == 3.29){
			$tjednisati = 17.41;
		}else{
		$tjednisati = $driver['broj_sati'] * 5;
		}
		$data['driverId'] = $driver['id'];
		$data['brutoPlaca'] = $brutoPlaca;
		$data['tjedniSati'] = $tjednisati;
		$data['pocetakPromjene'] = $pocetakPromjene;
		$data['pocetakRada'] = $pocetakRada;
		$data['tvrtka'] = $tvrtka;
		$data['driver'] = $driver;
		$data['fleet'] = $driver['fleet'];
		$data['page'] = $driver['vozac'];
		$data['svrha'] = 'UgovoroRadu';
    $dompdf = new Dompdf();
    $options = $dompdf->getOptions();
    $options->setDefaultFont('DejaVu Sans'); // This font supports a broader range of characters
    $dompdf->setOptions($options);

    // Generate the PDF content
		if($pocetakRada1 < 20231231){
			$html = view('adminDashboard/anekUgovoraPdf', $data);
		}else{
			$html = view('adminDashboard/aneksUgovora1Pdf', $data);
		}
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output the PDF to the client
    $filename = 'Aneks_ugovora_o_radu_' . $driver['vozac'] . '.pdf';
    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    echo $dompdf->output();		
		
	}
	
	public function aneksUgovora($id = null){
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$driverData = new DriverModel();
		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();
		$driver = $driverData->where('id', $id)->get()->getRowArray();
		$flota = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
//		print_r($flota);
//		die();
		$tvrtka_id = $flota['tvrtka_id'];
		$tvrtka = $tvrtkaModel->where('id', $tvrtka_id)->get()->getRowArray();
		$pocetakRadaVozac = $driver['pocetak_prijave'];
		$pocetakRadaVozac = str_replace('-', '', $pocetakRadaVozac);
		$pocetakRadaTvrtka = $tvrtka['pocetak_tvrtke'];
		$pocetakRadaTvrtka = str_replace('-', '', $pocetakRadaTvrtka);
		$pocetakPromjene = $driver['pocetak_promjene'];
		$pocetakPromjene = str_replace('-', '', $pocetakPromjene);
		$pocetakPromjene1 = str_replace('-', '', $pocetakPromjene);
		
			$godina = substr($pocetakPromjene,0,4) ;
			$mjesec = substr($pocetakPromjene,4,2) ;
			$dan = substr($pocetakPromjene,6,2) ;
			$pocetakPromjene = $dan.'.'.$mjesec.'.'.$godina.'.';

		
		if($pocetakRadaTvrtka < $pocetakRadaVozac){
			$godina = substr($pocetakRadaVozac,0,4) ;
			$mjesec = substr($pocetakRadaVozac,4,2) ;
			$dan = substr($pocetakRadaVozac,6,2) ;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
			$pocetakRada1 = $godina.$mjesec.$dan;
		}
		else{
			$godina = substr($pocetakRadaTvrtka,0,4) ;
			$mjesec = substr($pocetakRadaTvrtka,4,2) ;
			$dan = substr($pocetakRadaTvrtka,6,2) ;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
			$pocetakRada1 = $godina.$mjesec.$dan;
		}
		$brutoPlaca = 840.00;
		if($driver['broj_sati'] == 8){
			$brutoPlaca = 840;
		}
		elseif($driver['broj_sati'] == 6){
			$brutoPlaca = $brutoPlaca /4 * 3 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 4){
			$brutoPlaca = $brutoPlaca /2 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 2){
			$brutoPlaca = $brutoPlaca /4 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 1.5){
			$brutoPlaca = $brutoPlaca /5.33 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		elseif($driver['broj_sati'] == 3.29){
			$brutoPlaca = $brutoPlaca /8 * 3.29 ;
			$brutoPlaca = round($brutoPlaca, 2);
		}
		if($driver['broj_sati'] == 3.29){
			$tjednisati = 17.41;
		}else{
		$tjednisati = $driver['broj_sati'] * 5;
		}
		$data['driverId'] = $driver['id'];
		$data['brutoPlaca'] = $brutoPlaca;
		$data['tjedniSati'] = $tjednisati;
		$data['pocetakPromjene'] = $pocetakPromjene;
		$data['pocetakRada'] = $pocetakRada;
		$data['tvrtka'] = $tvrtka;
		$data['driver'] = $driver;
		$data['fleet'] = $driver['fleet'];
		$data['page'] = $driver['vozac'];
		$data['svrha'] = 'UgovoroRadu';
		if($pocetakRada1 < 20231231){
			echo view('adminDashboard/header', $data)
				.view('adminDashboard/anekUgovora');
		}else{
			echo view('adminDashboard/header', $data)
				.view('adminDashboard/aneksUgovora1');
		}
		
	}
	
	public function blagajnickiminmax($id = null){
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$driverData = new DriverModel();
		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();
		$vozilaModel = new VozilaModel();
		$vozilo = $vozilaModel->where('vozac_id', $id)->get()->getRowArray();
		$driver = $driverData->where('id', $id)->get()->getRowArray();
		$flota = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$tvrtka_id = $flota['tvrtka_id'];
		$tvrtka = $tvrtkaModel->where('id', $tvrtka_id)->get()->getRowArray();
		$pocetakRadaVozac = $driver['pocetak_prijave'];
		$pocetakRadaVozac = str_replace('-', '', $pocetakRadaVozac);
		$pocetakRadaTvrtka = $tvrtka['pocetak_tvrtke'];
		$pocetakRadaTvrtka = str_replace('-', '', $pocetakRadaTvrtka);
		if($pocetakRadaTvrtka < $pocetakRadaVozac){
			$godina = substr($pocetakRadaVozac,0,4) ;
			$mjesec = substr($pocetakRadaVozac,4,2) ;
			$dan = substr($pocetakRadaVozac,6,2) ;
			$pocrad = $dan .$mjesec .$godina;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
		}
		else{
			$godina = substr($pocetakRadaTvrtka,0,4) ;
			$mjesec = substr($pocetakRadaTvrtka,4,2) ;
			$dan = substr($pocetakRadaTvrtka,6,2) ;
			$pocrad = $dan .$mjesec .$godina;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
		}
		$voziloChangeDate = $vozilo['change_date'];
		$voziloChangeDate = str_replace('-','', $voziloChangeDate);
		if($voziloChangeDate > $pocetakRada){
			$godina = substr($voziloChangeDate,0,4) ;
			$mjesec = substr($voziloChangeDate,4,2) ;
			$dan = substr($voziloChangeDate,6,2) ;
			$pocNaj = $dan.'.'.$mjesec.'.'.$godina.'.';
			$pocetakNajma = $pocNaj;
		}else{
			$pocetakNajma = $pocetakRada;
			
		}
		
		$data['tvrtka'] = $tvrtka;
		$data['driver'] = $driver;
		$data['vozilo'] = $vozilo;
		$data['pocetakNajma'] = $pocetakNajma;
		$data['page'] = $driver['vozac'];
		
		echo view('adminDashboard/header', $data)
			.view('adminDashboard/blagajnickiminmax');
		
	}
	
	public function blagajnickiminmaxPdf($id = null){
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$driverData = new DriverModel();
		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();
		$vozilaModel = new VozilaModel();
		$vozilo = $vozilaModel->where('vozac_id', $id)->get()->getRowArray();
		$driver = $driverData->where('id', $id)->get()->getRowArray();
		$flota = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$tvrtka_id = $flota['tvrtka_id'];
		$tvrtka = $tvrtkaModel->where('id', $tvrtka_id)->get()->getRowArray();
		$pocetakRadaVozac = $driver['pocetak_prijave'];
		$pocetakRadaVozac = str_replace('-', '', $pocetakRadaVozac);
		$pocetakRadaTvrtka = $tvrtka['pocetak_tvrtke'];
		$pocetakRadaTvrtka = str_replace('-', '', $pocetakRadaTvrtka);
		if($pocetakRadaTvrtka < $pocetakRadaVozac){
			$godina = substr($pocetakRadaVozac,0,4) ;
			$mjesec = substr($pocetakRadaVozac,4,2) ;
			$dan = substr($pocetakRadaVozac,6,2) ;
			$pocrad = $dan .$mjesec .$godina;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
		}
		else{
			$godina = substr($pocetakRadaTvrtka,0,4) ;
			$mjesec = substr($pocetakRadaTvrtka,4,2) ;
			$dan = substr($pocetakRadaTvrtka,6,2) ;
			$pocrad = $dan .$mjesec .$godina;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
		}
		$voziloChangeDate = $vozilo['change_date'];
		$voziloChangeDate = str_replace('-','', $voziloChangeDate);
		if($voziloChangeDate > $pocetakRada){
			$godina = substr($voziloChangeDate,0,4) ;
			$mjesec = substr($voziloChangeDate,4,2) ;
			$dan = substr($voziloChangeDate,6,2) ;
			$pocNaj = $dan.'.'.$mjesec.'.'.$godina.'.';
			$pocetakNajma = $pocNaj;
		}else{
			$pocetakNajma = $pocetakRada;
			
		}
		
		$data['tvrtka'] = $tvrtka;
		$data['driver'] = $driver;
		$data['vozilo'] = $vozilo;
		$data['pocetakNajma'] = $pocetakNajma;
		$data['page'] = $driver['vozac'];
		
		    $dompdf = new Dompdf();
    $options = $dompdf->getOptions();
    $options->setDefaultFont('DejaVu Sans'); // This font supports a broader range of characters
    $dompdf->setOptions($options);

    // Generate the PDF content
    $html = view('adminDashboard/blagajnickiminmaxPdf', $data);
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output the PDF to the client
    $filename = 'Blagajnički_min_max_' . $driver['vozac'] . '.pdf';
    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    echo $dompdf->output();		
		
	}
	
	public function ugovoroNajmu($id = null){
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$driverData = new DriverModel();
		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();
		$vozilaModel = new VozilaModel();
		$vozilo = $vozilaModel->where('vozac_id', $id)->get()->getRowArray();
		$driver = $driverData->where('id', $id)->get()->getRowArray();
		$flota = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$tvrtka_id = $flota['tvrtka_id'];
		$tvrtka = $tvrtkaModel->where('id', $tvrtka_id)->get()->getRowArray();
		$pocetakRadaVozac = $driver['pocetak_prijave'];
		$pocetakRadaVozac = str_replace('-', '', $pocetakRadaVozac);
		$pocetakRadaTvrtka = $tvrtka['pocetak_tvrtke'];
		$pocetakRadaTvrtka = str_replace('-', '', $pocetakRadaTvrtka);
		if($pocetakRadaTvrtka < $pocetakRadaVozac){
			$godina = substr($pocetakRadaVozac,0,4) ;
			$mjesec = substr($pocetakRadaVozac,4,2) ;
			$dan = substr($pocetakRadaVozac,6,2) ;
			$pocrad = $dan .$mjesec .$godina;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
		}
		else{
			$godina = substr($pocetakRadaTvrtka,0,4) ;
			$mjesec = substr($pocetakRadaTvrtka,4,2) ;
			$dan = substr($pocetakRadaTvrtka,6,2) ;
			$pocrad = $dan .$mjesec .$godina;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
		}
		$voziloChangeDate = $vozilo['change_date'];
		$voziloChangeDate = str_replace('-','', $voziloChangeDate);
		if($voziloChangeDate > $pocetakRada){
			$godina = substr($voziloChangeDate,0,4) ;
			$mjesec = substr($voziloChangeDate,4,2) ;
			$dan = substr($voziloChangeDate,6,2) ;
			$pocNaj = $dan.'.'.$mjesec.'.'.$godina.'.';
			$pocetakNajma = $pocNaj;
		}else{
			$pocetakNajma = $pocetakRada;
			
		}
		
		$ukljuceniKm = $vozilo['cijena_tjedno'] / $vozilo['cijena_po_km'];
		$ukljuceniKM = round($ukljuceniKm, 0);
		
		$data['tvrtka'] = $tvrtka;
		$data['driver'] = $driver;
		$data['vozilo'] = $vozilo;
		$data['ukljuceniKM'] = $ukljuceniKM;
		$data['pocetakNajma'] = $pocetakNajma;
		$data['page'] = $driver['vozac'];
		
		echo view('adminDashboard/header', $data)
			.view('adminDashboard/ugovoroNajmu');
	}
	
	public function ugovoroNajmuPdf($id = null){
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$driverData = new DriverModel();
		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();
		$vozilaModel = new VozilaModel();
		$vozilo = $vozilaModel->where('vozac_id', $id)->get()->getRowArray();
		$driver = $driverData->where('id', $id)->get()->getRowArray();
		$flota = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$tvrtka_id = $flota['tvrtka_id'];
		$tvrtka = $tvrtkaModel->where('id', $tvrtka_id)->get()->getRowArray();
		$pocetakRadaVozac = $driver['pocetak_prijave'];
		$pocetakRadaVozac = str_replace('-', '', $pocetakRadaVozac);
		$pocetakRadaTvrtka = $tvrtka['pocetak_tvrtke'];
		$pocetakRadaTvrtka = str_replace('-', '', $pocetakRadaTvrtka);
		if($pocetakRadaTvrtka < $pocetakRadaVozac){
			$godina = substr($pocetakRadaVozac,0,4) ;
			$mjesec = substr($pocetakRadaVozac,4,2) ;
			$dan = substr($pocetakRadaVozac,6,2) ;
			$pocrad = $dan .$mjesec .$godina;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
		}
		else{
			$godina = substr($pocetakRadaTvrtka,0,4) ;
			$mjesec = substr($pocetakRadaTvrtka,4,2) ;
			$dan = substr($pocetakRadaTvrtka,6,2) ;
			$pocrad = $dan .$mjesec .$godina;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
		}
		$voziloChangeDate = $vozilo['change_date'];
		$voziloChangeDate = str_replace('-','', $voziloChangeDate);
		if($voziloChangeDate > $pocetakRada){
			$godina = substr($voziloChangeDate,0,4) ;
			$mjesec = substr($voziloChangeDate,4,2) ;
			$dan = substr($voziloChangeDate,6,2) ;
			$pocNaj = $dan.'.'.$mjesec.'.'.$godina.'.';
			$pocetakNajma = $pocNaj;
		}else{
			$pocetakNajma = $pocetakRada;
			
		}
		
		$ukljuceniKm = $vozilo['cijena_tjedno'] / $vozilo['cijena_po_km'];
		$ukljuceniKM = round($ukljuceniKm, 0);
		
		$data['tvrtka'] = $tvrtka;
		$data['driver'] = $driver;
		$data['vozilo'] = $vozilo;
		$data['ukljuceniKM'] = $ukljuceniKM;
		$data['pocetakNajma'] = $pocetakNajma;
		$data['page'] = $driver['vozac'];
		    $dompdf = new Dompdf();
    $options = $dompdf->getOptions();
    $options->setDefaultFont('DejaVu Sans'); // This font supports a broader range of characters
    $dompdf->setOptions($options);

    // Generate the PDF content
    $html = view('adminDashboard/ugovoroNajmuPdf', $data);
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output the PDF to the client
    $filename = 'ugovor_o_najmu_' . $driver['vozac'] . '.pdf';
    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment; filename=\"$filename\"");
    echo $dompdf->output();		

	}
	public function obracunDriver(){
        $session = session();
		$fleet = $session->get('fleet');
		
		
	}
	
	public function addDriver()
	{
        $session = session();
		$data = $session->get();
		$sessData = $session->get();
		$fleet = $session->get('fleet');
		$fleetAdmin = $session->get('name');
		helper(['form']);
        $data = [];
		$data['page'] = 'Add new Driver';
		$driversFind = new DriverModel();
		$flotaModel = new FlotaModel();
		$postavkeFlote = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$drivers = $driversFind->select('vozac')->where('fleet', $fleet)->where('aktivan', '1')->get()->getResultArray();
		$data['drivers'] = $drivers;

/*
c
		if(isset($drivers)){
			$data['drivers'] = $drivers;
			
		}else{
			$data['drivers'] = $sessData;
		}
*/		
		$data['postavkeFlote'] = $postavkeFlote;
		$data['sessData'] = $sessData;
		$data['fleet'] = $fleet;
		$data['role'] = $sessData['role'];
		
        echo view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/addDriver', $data)
			. view('footer');
		
	}
	
	public function driverUpdate()
	{
		        $session = session();
		$user_id = $session->get('id');
		$fleet = $session->get('fleet');

		if ($this->request->getVar('uberCheck')){$uberCheck = 1;}else{$uberCheck = 0;}
		if ($this->request->getVar('boltCheck')){$boltCheck = 1;}else{$boltCheck = 0;}
		if ($this->request->getVar('taximetarCheck')){$taximetarCheck = 1;}else{$taximetarCheck = 0;}
		if ($this->request->getVar('myPosCheck')){$myPosCheck = 1;}else{$myPosCheck = 0;}
		$whatsApp = $this->request->getVar('whatsApp');
		$whatsApp = str_replace(" ", "", $whatsApp);
		$id = $this->request->getVar('id');
		$driverModel = new DriverModel();
		$zasticeniIBAN = $this->request->getVar('zasticeniIBAN');
		$IBAN = $this->request->getVar('IBAN');
		$IBAN = str_replace(" ", "", $IBAN);
		$zasticeniIBAN = str_replace(" ", "", $zasticeniIBAN);
			$strani_IBAN = $this->request->getVar('strani_IBAN');
			$strani_IBAN = str_replace(" ", "", $strani_IBAN);

		$data = [
			'vozac'					=> $this->request->getVar('vozac'),
			'ime'					=> $this->request->getVar('ime'), 
			'prezime'				=> $this->request->getVar('prezime'), 
			'email'					=> $this->request->getVar('email'), 
			'mobitel'				=> $this->request->getVar('mobitel'), 
			'adresa'				=> $this->request->getVar('adresa'), 
			'grad'					=> $this->request->getVar('grad'), 
			'drzava'				=> $this->request->getVar('drzava'), 
			'postanskiBroj'			=> $this->request->getVar('postanskiBroj'), 
			'dob'					=> $this->request->getVar('dob'), 
			'oib'					=> $this->request->getVar('oib'), 
			'uber'					=> $uberCheck, 
			'bolt'					=> $boltCheck, 
			'taximetar'				=> $taximetarCheck, 
			'myPos'					=> $myPosCheck, 
			'blagMin'				=> $this->request->getVar('blagMin'), 
			'blagMax'				=> $this->request->getVar('blagMax'), 
			'referal_reward'		=> $this->request->getVar('referal_reward'), 
			'refered_by'			=> $this->request->getVar('refered_by'), 
			'vrsta_provizije'		=> $this->request->getVar('vrsta_provizije'), 
			'iznos_provizije'		=> $this->request->getVar('iznos_provizije'), 
			'popust_na_proviziju'	=> $this->request->getVar('popust_na_proviziju'), 
			'popust_na_taximetar'	=> $this->request->getVar('popust_na_taximetar'), 
			'uber_unique_id'		=> $this->request->getVar('uber_unique_id'), 
			'bolt_unique_id'		=> $this->request->getVar('bolt_unique_id'), 
			'myPos_unique_id'		=> $this->request->getVar('myPos_unique_id'),
			'taximetar_unique_id'	=> $this->request->getVar('taximetar_unique_id'),
			'nacin_rada'			=> $this->request->getVar('nacin_rada'),
			'vrsta_nagrade'			=> $this->request->getVar('vrsta_nagrade'),
			'isplata'				=> $this->request->getVar('isplata'),
			'whatsApp'				=> $whatsApp,
			'provizijaNaljepnice'	=> $this->request->getVar('provizijaNaljepnice'),
			'IBAN'					=> $IBAN,
			'pocetak_rada'			=> $this->request->getVar('pocetak_rada'),
			'sezona'				=> $this->request->getVar('sezona'),
			'zasticeniIBAN'			=> $zasticeniIBAN,
			'strani_IBAN'			=> $strani_IBAN,
			];
		$driverModel->update($id, $data);
				
		return redirect()->to('/index.php/drivers/'. $id);
		
	}
	
	// OVDJE SU PROMJENE VEZANE ZA PRIJAVU
	
	public function driverPrijavaUpdate()
	{
		        $session = session();
		$user_id = $session->get('id');
		$fleet = $session->get('fleet');

		if ($this->request->getVar('uberCheck')){$uberCheck = 1;}else{$uberCheck = 0;}
		if ($this->request->getVar('boltCheck')){$boltCheck = 1;}else{$boltCheck = 0;}
		if ($this->request->getVar('taximetarCheck')){$taximetarCheck = 1;}else{$taximetarCheck = 0;}
		if ($this->request->getVar('myPosCheck')){$myPosCheck = 1;}else{$myPosCheck = 0;}
		$id = $this->request->getVar('id');
		$driverModel = new DriverModel();
		$zasticeniIBAN = $this->request->getVar('zasticeniIBAN');
		$IBAN = $this->request->getVar('IBAN');
		if(isset($IBAN)){
			$IBAN = str_replace(" ", "", $IBAN);
		}
		if(isset($zasticeniIBAN)){
			$zasticeniIBAN = str_replace(" ", "", $zasticeniIBAN);
		}
			$strani_IBAN = $this->request->getVar('strani_IBAN');
		if(isset($strani_IBAN)){
			$strani_IBAN = str_replace(" ", "", $strani_IBAN);
		}

		$data = [
			'radno_mjesto'			=> $this->request->getVar('radno_mjesto'),
			'pocetak_prijave'		=> $this->request->getVar('pocetak_prijave'),
			'pocetak_promjene'		=> $this->request->getVar('pocetak_promjene'),
			'prijava'				=> $this->request->getVar('prijava'),
			'kraj_prijave'			=> $this->request->getVar('kraj_prijave'),
			'vrsta_zaposlenja'		=> $this->request->getVar('vrsta_zaposlenja'),
			'broj_sati'				=> $this->request->getVar('broj_sati'),
			'radniOdnos'			=> $this->request->getVar('radniOdnos'),
			'aktivan'				=> $this->request->getVar('aktivan'),
			];
		$prijava = $this->request->getVar('prijava');
		$voz1 = $driverModel->where('id', $id)->get()->getRowArray();
		$currentPrijavaData = array(
			'ime' => $voz1['ime'],
			'prezime' => $voz1['prezime'],
			'dob' => $voz1['dob'],
			'OIB' => $voz1['oib'],
			'broj_sati' => $voz1['broj_sati'],
			'pocetak_prijave' => $voz1['pocetak_prijave'],
			'kraj_prijave'			=> $voz1['kraj_prijave'],
			'vrsta_zaposlenja'		=> $voz1['vrsta_zaposlenja'],
			'IBAN' => $voz1['IBAN'],
			'zasticeniIBAN' => $voz1['zasticeniIBAN'],
			'strani_IBAN' => $voz1['strani_IBAN'],
			'radno_mjesto' => $voz1['radno_mjesto'],		
		);
		
		$newPrijavaData = array(
			'ime'					=> $this->request->getVar('ime'), 
			'prezime'				=> $this->request->getVar('prezime'), 
			'dob'					=> $this->request->getVar('dob'), 
			'OIB'					=> $this->request->getVar('oib'), 
			'broj_sati'				=> $this->request->getVar('broj_sati'),
			'pocetak_prijave'		=> $this->request->getVar('pocetak_prijave'),
			'kraj_prijave'			=> $this->request->getVar('kraj_prijave'),
			'vrsta_zaposlenja'		=> $this->request->getVar('vrsta_zaposlenja'),
			'pocetak_promjene'		=> $this->request->getVar('pocetak_promjene'),
			'IBAN'					=> $IBAN,
			'zasticeniIBAN'			=> $zasticeniIBAN,
			'strani_IBAN'			=> $strani_IBAN,
			'radno_mjesto'			=> $this->request->getVar('radno_mjesto'),
		
		);

		
		$driverModel->update($id, $data);
		
		$currentChecksum = md5(serialize($currentPrijavaData));
		$newChecksum = md5(serialize($newPrijavaData));
		
		if ($currentChecksum !== $newChecksum) {
			if($prijava != 1){
				$prekid_rada = $this->request->getVar('pocetak_promjene');
			}else{
				$prekid_rada = '';
			}
			$prijavaData = array(

				'ime'					=> $this->request->getVar('ime'), 
				'prezime'				=> $this->request->getVar('prezime'), 
				'dob'					=> $this->request->getVar('dob'), 
				'OIB'					=> $this->request->getVar('oib'), 
				'broj_sati'				=> $this->request->getVar('broj_sati'),
				'pocetak_prijave'		=> $this->request->getVar('pocetak_prijave'),
				'vrsta_zaposlenja'		=> $this->request->getVar('vrsta_zaposlenja'),
				'pocetak_promjene'		=> $this->request->getVar('pocetak_promjene'),
				'pocetak_promjene'		=> $this->request->getVar('pocetak_promjene'),
				'prekid_rada'			=> $prekid_rada,
				'vozac_id'				=> $id,		
				'IBAN'					=> $IBAN,
				'zasticeniIBAN'			=> $zasticeniIBAN,
				'strani_IBAN'			=> $strani_IBAN,
				'radno_mjesto'			=> $this->request->getVar('radno_mjesto'),
				'prvi_unos'				=> 0,
				'nadopuna'				=> 1,
				'user_id'				=> $user_id,
				'fleet'					=> $fleet,
				);


				$prijaveModel = new PrijaveModel();
				$prijaveModel->insert($prijavaData);
			if($prijava != 1){
				return redirect()->to('/index.php/kreirajRaskid/'. $id);
			}
		}		
		return redirect()->to('/index.php/drivers/'. $id);
		
	}
	
	public function raskidUgovora(){
        $session = session();
		$role = $session->get('role');
		$fleet = $session->get('fleet');
		$driverModel = new DriverModel();
		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();
		$vozilaModel = new VozilaModel();
		
		$vozac_id = $this->request->getVar('vozac_id');
		$driver = $driverModel->where('id', $vozac_id)->get()->getRowArray();
		$pocetakPromjene = $driver['pocetak_promjene'];
		$pocetakPromjene = str_replace('-', '', $pocetakPromjene);
		$pocetakPromjene1 = str_replace('-', '', $pocetakPromjene);
		$pocetakRadaVozac = $driver['pocetak_prijave'];
		$pocetakRadaVozac = str_replace('-', '', $pocetakRadaVozac);
			$godina = substr($pocetakRadaVozac,0,4) ;
			$mjesec = substr($pocetakRadaVozac,4,2) ;
			$dan = substr($pocetakRadaVozac,6,2) ;
			$pocrad = $dan .$mjesec .$godina;
			$pocetakRada = $dan.'.'.$mjesec.'.'.$godina.'.';
		
			$godina = substr($pocetakPromjene,0,4) ;
			$mjesec = substr($pocetakPromjene,4,2) ;
			$dan = substr($pocetakPromjene,6,2) ;
			$pocetakPromjene = $dan.'.'.$mjesec.'.'.$godina.'.';
		
		$vrstaRaskida = $this->request->getVar('vrstaRaskida');
		
		$razlog = "";
		if($vrstaRaskida == 0){
			$raskid = 'sporazumni';
		}elseif($vrstaRaskida == 1){
			$raskid = 'redovitiOdRadnika';
		}elseif($vrstaRaskida == 2){
			$raskid = 'redovitiOdPoslodavca';
			$razlog = $this->request->getVar('redovitiOtkaz');
			if($razlog == 0){
				$razlog = 'neOdazivaSe';
			}elseif($razlog == 1){
				$razlog = 'neRadi';
			}elseif($razlog == 2){
				$razlog = 'neodgovorno';
			}
		}elseif($vrstaRaskida == 3){
			$raskid = 'izvanredni';
			$razlog = $this->request->getVar('izvanredniOtkaz');
			if($razlog == 0){
				$razlog = 'gotovina';
			}elseif($razlog == 1){
				$razlog = 'ilegalne';
			}elseif($razlog == 2){
				$razlog = 'grubo';
			}
		}

		$flota = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$tvrtka_id = $flota['tvrtka_id'];
		$tvrtka = $tvrtkaModel->where('id', $tvrtka_id)->get()->getRowArray();
		
		$data['pocetakRada'] = $pocetakRada;
		$data['pocetakPromjene'] = $pocetakPromjene;
		$data['role'] = $role;
		$data['fleet'] = $fleet;
		$data['driver'] = $driver;
		$data['fleet'] = $fleet;
		$data['tvrtka'] = $tvrtka;
		$data['raskid'] = $raskid;
		$data['razlog'] = $razlog;
		$data['svrha'] = 'UgovoroRadu';
		$data['page'] = 'Raskid ugovora';
		
		
			echo view('adminDashboard/header', $data)
				.view('adminDashboard/raskidUgovora');

		
	}
	
	
	public function kreirajRaskid($id = null){
        $session = session();
		$role = $session->get('role');
		$fleet = $session->get('fleet');
		$driverModel = new DriverModel();
		$tvrtkaModel = new TvrtkaModel();
		$flotaModel = new FlotaModel();
		$vozilaModel = new VozilaModel();
		$vozilo = $vozilaModel->where('vozac_id', $id)->get()->getRowArray();
		$driver = $driverModel->where('id', $id)->get()->getRowArray();
		$flota = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$tvrtka_id = $flota['tvrtka_id'];
		$tvrtka = $tvrtkaModel->where('id', $tvrtka_id)->get()->getRowArray();

		$driver = $driverModel->where('id', $id)->get()->getRowArray();
		
		$data['role'] = $role;
		$data['fleet'] = $fleet;
		$data['driver'] = $driver;
		$data['flota'] = $flota;
		$data['tvrtka'] = $tvrtka;
		$data['vozilo'] = $vozilo;
		$data['page'] = 'Kreiraj raskid ugovora';
		
		
			echo view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/kreirajRaskid')
				. view('footer');
		
		
	}
	
	public function vozila(){
        $session = session();
		$role = $session->get('role');
		$fleet = $session->get('fleet');
		$vozilaModel = new VozilaModel();
		$vozila = $vozilaModel->where('fleet', $fleet)->get()->getResultArray();
		$driverModel = new DriverModel();
		$vozila1 = array();
		foreach($vozila as $vozilo){
			$vozac = $driverModel->where('id', $vozilo['vozac_id'])->get()->getRowArray();
			$vozac = $vozac['vozac'];
			$vozilo['vozac'] = $vozac;
			$vozila1[] = $vozilo;
		}
		
				
		$data['role'] = $role;
		$data['fleet'] = $fleet;
		$data['page'] = 'Vozila';
		$data['vozila'] = $vozila1;
		
			echo view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/vozila')
				. view('footer');
	}
	
	public function addVoziloSave(){
        $session = session();
		$fleet = $session->get('fleet');
		helper(['form']);
		
        $rules = [
            'proizvodac' => 'required|max_length[255]|min_length[1]',
            'model' => 'required|max_length[255]|min_length[1]',
            'reg' => 'required|max_length[255]|min_length[3]',
            'vozac_id' => 'required|max_length[255]|min_length[1]',
            'cijena_tjedno' => 'required|max_length[255]|min_length[1]',
            'cijena_po_km' => 'required|max_length[255]|min_length[1]',
			];
		$id = $this->request->getVar('vozac_id');
		if($this->validate($rules)){
			$vozilaModel = new VozilaModel();
			$data = [
			'proizvodac'					=> $this->request->getVar('proizvodac'),
			'model'							=> $this->request->getVar('model'), 
			'reg'							=> $this->request->getVar('reg'), 
			'vozac_id'						=> $this->request->getVar('vozac_id'), 
			'cijena_tjedno'					=> $this->request->getVar('cijena_tjedno'), 
			'cijena_po_km'					=> $this->request->getVar('cijena_po_km'), 
			'godina'						=> $this->request->getVar('godina'), 
			'placa_firma'					=> $this->request->getVar('placa_firma'), 
			'fleet'							=> $fleet
				];
			$vozilaModel->save($data);
			$session->setFlashdata('msgVozilo', ' Uspješno dodano vozilo.');
			session()->setFlashdata('alert-class', 'alert-success');
            return redirect()->to('/index.php/drivers/'. $id);
		}else{
			$session->setFlashdata('msgVozilo', ' Vozilo nije dodano.');
			session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to('/index.php/drivers/'. $id);		}
	}
	
	public function addVoziloUpdate(){
        $session = session();
		$fleet = $session->get('fleet');
		helper(['form']);
		
		$currentData = ($this->request);
        $rules = [
            'proizvodac' => 'required|max_length[255]|min_length[1]',
            'model' => 'required|max_length[255]|min_length[1]',
            'reg' => 'required|max_length[255]|min_length[3]',
             'cijena_tjedno' => 'required|max_length[255]|min_length[1]',
            'cijena_po_km' => 'required|max_length[255]|min_length[1]',
           'vozac_id' => 'required|max_length[255]|min_length[1]',
			];
		$vozac_id = $this->request->getVar('vozac_id');
		if($this->validate($rules)){
			$id = $this->request->getVar('id');
			$vozilaModel = new VozilaModel();
			$data = [
			'id'							=> $id,
			'proizvodac'					=> $this->request->getVar('proizvodac'),
			'model'							=> $this->request->getVar('model'), 
			'reg'							=> $this->request->getVar('reg'), 
			'cijena_tjedno'					=> $this->request->getVar('cijena_tjedno'), 
			'cijena_po_km'					=> $this->request->getVar('cijena_po_km'), 
			'vozac_id'						=> $this->request->getVar('vozac_id'), 
			'godina'						=> $this->request->getVar('godina'), 
			'change_date'					=> $this->request->getVar('change_date'), 
			'placa_firma'					=> $this->request->getVar('placa_firma'), 
				];
			
			$vozilaModel->update($id, $data);;
            return redirect()->to('/index.php/drivers/'. $vozac_id);
		}
	}
	
	public function addDriverSave()
	{
        $session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$user_id = $session->get('id');
		helper(['form']);
		$flotaModel = new FlotaModel();
		$postavkeFlote = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$currentDate = date('Y-m-d');
		if ($this->request->getVar('uberCheck')){$uberCheck = 1;}else{$uberCheck = 0;}
		if ($this->request->getVar('boltCheck')){$boltCheck = 1;}else{$boltCheck = 0;}
		if ($this->request->getVar('taximetarCheck')){$taximetarCheck = 1;}else{$taximetarCheck = 0;}
		if ($this->request->getVar('myPosCheck')){$myPosCheck = 1;}else{$myPosCheck = 0;}
		if ($this->request->getVar('pocetak_rada')){$pocetakRada = $this->request->getVar('pocetak_rada');}else{$pocetakRada = $currentDate;}
		if ($this->request->getVar('pocetak_prijave')){$pocetakPrijave = $this->request->getVar('pocetak_prijave');}else{$pocetakPrijave = $currentDate;}
		if ($this->request->getVar('kraj_prijave')){$krajPrijave = $this->request->getVar('kraj_prijave');}else{$krajPrijave = 0;}
		$vozac = $this->request->getVar('ime'). ' '. $this->request->getVar('prezime');
		$whatsApp = $this->request->getVar('whatsApp');
		$whatsApp = str_replace(" ", "", $whatsApp);

		$uber_unique_id = $vozac;
		$myPos_unique_id = $vozac;
		$taximetar_unique_id = $this->request->getVar('email');
		$myPos_unique_id = str_replace("č", "c", $myPos_unique_id);
		$myPos_unique_id = str_replace("ć", "c", $myPos_unique_id);
		$myPos_unique_id = str_replace("š", "s", $myPos_unique_id);
		$myPos_unique_id = str_replace("ž", "z", $myPos_unique_id);
		$myPos_unique_id = str_replace("đ", "d", $myPos_unique_id);
		
		$currentData = ($this->request);
        $rules = [
            'ime' => 'required|max_length[255]|min_length[3]',
            'prezime' => 'required|max_length[255]|min_length[3]',
            'email' => 'valid_email|required|max_length[255]|min_length[3]',
            'mobitel' => 'required|max_length[255]|min_length[3]',
            'adresa' => 'max_length[255]|min_length[3]',
            'grad' => 'required|max_length[255]|min_length[3]',
            'drzava' => 'required|max_length[255]|min_length[3]',
            'dob' => 'required|max_length[255]|min_length[3]',
            'oib' => 'required|max_length[11]|min_length[11]',
            'uberCheck' => 'max_length[255]|min_length[1]',
            'boltCheck' => 'max_length[255]|min_length[1]',
            'taximetarCheck' => 'max_length[255]|min_length[1]',
            'myPosCheck' => 'max_length[255]|min_length[1]',
            'refered_by' => 'required|max_length[255]|min_length[3]',
            
        ];


		if($this->validate($rules)){
			$driverModel = new DriverModel();
			
			$IBAN = $this->request->getVar('IBAN');
			$IBAN = str_replace(" ", "", $IBAN);
			$zasticeniIBAN = $this->request->getVar('zasticeniIBAN');
			$zasticeniIBAN = str_replace(" ", "", $zasticeniIBAN);
			$strani_IBAN = $this->request->getVar('strani_IBAN');
			$strani_IBAN = str_replace(" ", "", $strani_IBAN);
			$driverData = [
			'vozac'					=> $vozac,
			'ime'					=> $this->request->getVar('ime'), 
			'prezime'				=> $this->request->getVar('prezime'), 
			'email'					=> $this->request->getVar('email'), 
			'mobitel'				=> $this->request->getVar('mobitel'), 
			'whatsApp'				=> $whatsApp,
			'adresa'				=> $this->request->getVar('adresa'), 
			'grad'					=> $this->request->getVar('grad'), 
			'drzava'				=> $this->request->getVar('drzava'), 
			'postanskiBroj'			=> $this->request->getVar('postanskiBroj'), 
			'dob'					=> $this->request->getVar('dob'), 
			'oib'					=> $this->request->getVar('oib'), 
			'uber'					=> $uberCheck, 
			'bolt'					=> $boltCheck, 
			'taximetar'				=> $taximetarCheck, 
			'myPos'					=> $myPosCheck, 
			'refered_by'			=> $this->request->getVar('refered_by'), 
			'referal_reward'		=> $this->request->getVar('referal_reward'), 
			'blagMin'				=> $this->request->getVar('blagMin'), 
			'blagMax'				=> $this->request->getVar('blagMax'), 
			'vrsta_provizije'		=> $this->request->getVar('vrsta_provizije'), 
			'nacin_rada'			=> $this->request->getVar('nacin_rada'), 
			'iznos_provizije'		=> $this->request->getVar('iznos_provizije'), 
			'popust_na_proviziju'	=> $this->request->getVar('popust_na_proviziju'), 
			'popust_na_taximetar'	=> $this->request->getVar('popust_na_taximetar'), 
			'uber_unique_id'		=> $uber_unique_id, 
			'bolt_unique_id'		=> $uber_unique_id, 
			'myPos_unique_id'		=> $myPos_unique_id,
			'taximetar_unique_id'	=> $taximetar_unique_id,
			'pocetak_rada'			=> $pocetakRada,
			'pocetak_prijave'		=> $pocetakPrijave,
			'kraj_prijave'			=> $krajPrijave,
			'vrsta_zaposlenja'		=> $this->request->getVar('vrsta_zaposlenja'),
			'prijava'				=> $this->request->getVar('prijava'),
			'broj_sati'				=> $this->request->getVar('broj_sati'),
			'vrsta_nagrade'			=> $this->request->getVar('vrsta_nagrade'),
			'aktivan'				=> $this->request->getVar('aktivan'),
			'radno_mjesto'			=> $this->request->getVar('radno_mjesto'),
			'nacin_rada'			=> $this->request->getVar('nacin_rada'),
			'isplata'				=> $this->request->getVar('isplata'),
			'sezona'				=> $this->request->getVar('sezona'),
			'radniOdnos'			=> $this->request->getVar('radniOdnos'),
			'provizijaNaljepnice'	=> $this->request->getVar('provizijaNaljepnice'),
			'IBAN'					=> $IBAN,
			'zasticeniIBAN'			=> $zasticeniIBAN,
			'whatsApp'				=> $this->request->getVar('whatsApp'),
			'strani_IBAN'			=> $strani_IBAN,
			'fleet'					=> $fleet,
			];
			if($driverModel->save($driverData)){
				$id = $driverModel->insertID;
				$prijavaData = array(
					
				'ime'					=> $this->request->getVar('ime'), 
				'prezime'				=> $this->request->getVar('prezime'), 
				'dob'					=> $this->request->getVar('dob'), 
				'oib'					=> $this->request->getVar('oib'), 
				'broj_sati'				=> $this->request->getVar('broj_sati'),
				'pocetak_prijave'		=> $pocetakPrijave,
				'kraj_prijave'			=> $krajPrijave,
				'vrsta_zaposlenja'		=> $this->request->getVar('vrsta_zaposlenja'),
				'vozac_id'				=> $id,		
				'IBAN'					=> $IBAN,
				'zasticeniIBAN'			=> $zasticeniIBAN,
				'strani_IBAN'			=> $strani_IBAN,
				'radno_mjesto'			=> $this->request->getVar('radno_mjesto'),
				'prvi_unos'				=> 1,
				'nadopuna'				=> 0,
				'user_id'				=> $user_id,
				'fleet'					=> $fleet,
				);
				
				$prijaveModel = new PrijaveModel();
				$prijaveModel->insert($prijavaData);
			
				return redirect()->to('/index.php/drivers/'.$id);
			}
			else{
			$data['page'] = 'Add new Driver';
			$data['role']= $role;
			$driversFind = new DriverModel();
			$drivers = $driversFind->select('vozac')->get()->getResultArray();

			$data['drivers'] = $drivers;
			$data['currentData'] = $driverData;
            $data['validation'] = $this->validator;
			$data['fleet'] = $fleet;
			$data['postavkeFlote'] = $postavkeFlote;
			echo view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/addDriver')
				. view('footer');
			}
            
		}else{
			$data['page'] = 'Add new Driver';
			$driversFind = new DriverModel();
			$drivers = $driversFind->select('vozac')->get()->getResultArray();

			$data['drivers'] = $drivers;
			$data['role']= $role;
			$data['currentData'] = $currentData;
            $data['validation'] = $this->validator;
			$data['postavkeFlote'] = $postavkeFlote;
			$data['fleet'] = $fleet;
			echo view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/addDriver')
				. view('footer');
        }

	}

	
	public function knjigovodstvo($week = null){
        $session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$driversModel = new DriverModel();
		$vozilaModel = new VozilaModel();
		$obracunModel = new ObracunModel();

		
		$knjigoReport = array();
		if(isset($week)){
		$yearNo = substr($week, 0, 4);
		$weekNo = substr($week, 4, 2);
			$obracuni = $obracunModel->where('fleet', $fleet)->where('week', $week)->get()->getResultArray();
			foreach($obracuni as $obracun){
				$dateTime = new \DateTime();
				$dateTime->setISODate($yearNo, $weekNo);
				$result['start_date'] = $dateTime->format('Ymd');
				$result['weekStartMonth'] = $dateTime->format('m');
				
				$dateTime->modify('+6 days');
				$result['end_date'] = $dateTime->format('Ymd');
				$result['weekEndMonth'] = $dateTime->format('m');
				$dateTime->setISODate($yearNo, $weekNo);
				
				$dateTime->modify('+9 days');
				$result['dan_isplate'] = $dateTime->format('Ymd');
				
				$dateTime->modify('-2 days');
				$result['dan_obracuna'] = $dateTime->format('Ymd');
				$danObracuna = $result['dan_obracuna'];
				$danIsplate = $result['dan_isplate'];
				$start_date = $result['start_date'];
				$end_date = $result['end_date'];
				$prviDan = substr($start_date, 6, 2).'.'.substr($start_date, 4, 2).'.'.substr($start_date, 0, 4).'.';
				$zadnjiDan = substr($end_date, 6, 2).'.'.substr($end_date, 4, 2).'.'.substr($end_date, 0, 4).'.';
				$danIsplate = substr($danIsplate, 6, 2).'.'.substr($danIsplate, 4, 2).'.'.substr($danIsplate, 0, 4).'.';
				$danObracuna = substr($danObracuna, 6, 2).'.'.substr($danObracuna, 4, 2).'.'.substr($danObracuna, 0, 4).'.';
				
				$data['prviDan'] = $prviDan;
				$data['zadnjiDan'] = $zadnjiDan;
				$data['danObracuna'] = $danObracuna;
				
				$neto = $obracun['ukupnoNeto'];
				$vozacId = $obracun['vozac_id'];
				$vozac = $obracun['vozac'];
				$vozilo = $vozilaModel->where('vozac_id', $vozacId)->get()->getRowArray();
				
				$osnovnaCijena = $vozilo['cijena_tjedno'];
				$cijenaPoKm = $vozilo['cijena_po_km'];
				$ukljuceniKm = $osnovnaCijena / $cijenaPoKm;
				$ukljuceniKm = round($ukljuceniKm, 0);
				
				$prijedeniKm = $neto / $cijenaPoKm;
				$prijedeniKm = round($prijedeniKm, 0);
				
				if($prijedeniKm > $ukljuceniKm){
					$kmZaDoplatit = $prijedeniKm - $ukljuceniKm;
					$dug = $kmZaDoplatit * $cijenaPoKm;
					
				}else{
					
					$dug = 0;
				}
				
				$knjigoReport[]=array(
					'id'				=> $obracun['id'],
					'proizvodac' 		=> $vozilo['proizvodac'], 
					'model' 			=> $vozilo['model'], 
					'reg'  				=> $vozilo['reg'],
					'vozac'				=> $vozac,
					'osnovnaCijena'  	=> $osnovnaCijena,
					'cijenaPoKm'  		=> $cijenaPoKm,
					'ukljuceniKm'  		=> $ukljuceniKm,
					'prijedeniKm'  		=> $prijedeniKm,
					'dug' 				=> $dug,
					'obrRazdobljeOd'  	=> $prviDan,
					'obrRazdobljeDo' 	=> $zadnjiDan,
					'danIsplate'  		=> $danIsplate,
					'danObracuna'  		=> $danObracuna,
					
				
				);
				
			}
			
		
		$data['obracuni'] = $obracuni;
		$data['knjigoReport'] = $knjigoReport;
		$data['page'] = 'Knjigovodstvo';
		$data['fleet'] = $fleet;
		
		echo view('adminDashboard/header', $data)
		. view('adminDashboard/navBar')
		. view('adminDashboard/knjigovodstvo')
		. view('footer');
		
		
		}else{
			
		
		}
		
		
	}
	
	
	public function obracun($week = null)
	{
        $session = session();
		$fleet = $session->get('fleet');
		$data = $session->get();
		
		$myPosObracun = new MyPosReportModel();
		$uberObracun = new UberReportModel();
		$boltObracun = new BoltReportModel();
		$fiskalizacijaModel = new FiskalizacijaModel();
		$obracunFirmaModel = new ObracunFirmaModel();
		$driversFind = new DriverModel();
		$referalBonusModel = new ReferalBonusModel();
		$doprinosiModel = new DoprinosiModel();
		
		if(isset($week)){
			$obracunModel = new ObracunModel();
			$obracunPostoji = 0;
			$obracunPostoji = $obracunModel->where(['week' => $week])->where('fleet', $fleet)->countAllResults();
			if($obracunPostoji < 1){
				$yearNo = substr($week, 0, 4);
				$weekNo = substr($week, 4, 2);
				$date=date_create();
				date_isodate_set($date,$yearNo,$weekNo);
				$monthNo = date_format($date,"m");
				$fiskMonthNo = date_format($date,"Ym");
				
				$dateTime = new \DateTime();
				$dateTime->setISODate($yearNo, $weekNo);
				$result['start_date'] = $dateTime->format('Ymd');
				$result['st_date'] = $dateTime->format('Y-m-d');
				$result['weekStartMonth'] = $dateTime->format('m');
				$dateTime->modify('+7 days');
				$result['end_date'] = $dateTime->format('Ymd');
				$result['en_date'] = $dateTime->format('Y-m-d');
				$result['weekEndMonth'] = $dateTime->format('m');
				$start_date = $result['start_date'];
				$st_date = $result['st_date'];
				$en_date = $result['en_date'];
				$end_date = $result['end_date'];
				$wSMonth = $result['weekStartMonth'];
				$wEMonth = $result['weekEndMonth'];
				
				$drivers = $driversFind->where('fleet', $fleet)->where('aktivan', '1')->get()->getResultArray();
				
				$uberNetoSvi = 0;
				$uberGotovinaSvi = 0;
				$uberRazlikaSvi = 0;
				$boltNetoSvi = 0;
				$boltRazlikaSvi = 0;
				$boltGotovinaSvi = 0;
				$myPosNetoSvi = 0;
				$firmaProvizija = 0;
				$firmaFiskalizacijaBolt = 0;
				$firmaFiskalizacijaUber = 0;
				$firmaTaximetar = 0;
				$doprinosiSvi = 0;
				$placaSvi = 0;
				$obracun = array();
				$fiskalizacija = array();
				foreach($drivers as $driver){		
					$fiskalizacijaNaplata = false;
						if($wSMonth != $wEMonth){
							$fiskalizacijaNaplata = true;
						}
					$referal = array();
						$referalsFind = new DriverModel();
						$referals = $referalsFind->select('vozac, referal_reward, vrsta_provizije')->where(['refered_by' => $driver['vozac']])->get()->getResultArray();
						if($referals != null){
							foreach($referals as $ref){
								$referal[] = $ref;
							}
						}
					$referal_json = json_encode($referal);

					$boltNeto = 0;
					$boltGotovina = 0;
					$boltRazlika = 0;
					$boltFiskalizacija = false;
					if($driver['bolt'] != 0){
						$bolt = $boltObracun->where(['report_for_week' => $week])->where(['vozac' => $driver['bolt_unique_id']])->get()->getRowArray();
						if($bolt != null){
							$boltNeto = (float) $bolt['Bruto_iznos'] + (float) $bolt['Otkazna_naknada'] + (float) $bolt['Naknada_za_rezervaciju_placanje'] + (float) $bolt['Naknada_za_rezervaciju_odbitak'] + (float) $bolt['Naknada_za_cestarinu'] + (float) $bolt['Bolt_naknada'] + (float) $bolt['Bonus'] + (float) $bolt['Nadoknade'] + (float) $bolt['Povrati_novca'] + (float) $bolt['Napojnica'];
							$boltGotovina = (float) $bolt['Voznje_placene_gotovinom_prikupljena_gotovina'];
							$boltRazlika = $boltNeto + $boltGotovina;
							$boltNetoSvi += $boltNeto;
							$boltGotovinaSvi += $boltGotovina;
							$boltRazlikaSvi += $boltRazlika;
							$boltFiskalizacija = true;


						}
					}
					$uberNeto = 0;
					$uberGotovina = 0;
					$uberRazlika = 0;
					$uberFiskalizacija = false;
					if($driver['uber'] != 0){
						$uber = $uberObracun->where(['report_for_week' => $week])->where(['vozac' => $driver['uber_unique_id']])->get()->getRowArray();
						if($uber != null){
							$uberNeto = (float) $uber['Ukupna_zarada'] + (float) $uber['Povrati_i_troskovi_Povrati_Pristojba_za_zracnu_luku'] + (float) $uber['Povrati_i_troskovi_Povrati_Cestarina'];
							$uberGotovina = (float) $uber['Isplate_Naplaceni_iznos_u_gotovini'];
							$uberRazlika = $uberNeto + $uberGotovina;
							$uberNetoSvi += $uberNeto;
							$uberGotovinaSvi += $uberGotovina;
							$uberRazlikaSvi += $uberRazlika;
							$uberFiskalizacija = true;
						}
					}

					$myPosNeto = 0;
					$myPosGotovina = 0;
					$myPosRazlika = 0;
					$myPosTransakcije = 0;
					if($driver['myPos'] != 0){
						$myPos = $myPosObracun->where(['report_for_week' => $week])->where(['Terminal_name' => $driver['myPos_unique_id']])->get()->getResultArray();
						
						if($myPos != null){
							$myPosNeto = 0;
							$myPosTransakcije = json_encode($myPos);
							foreach($myPos as $mp){
								$myPosNeto += $mp['Amount'];
							}
							$myPosGotovina = 0;
							$myPosRazlika = $myPosNeto;
							$myPosNetoSvi += $myPosNeto;
						}
					}


					$provizija = 0;

					$ukupnoNeto = $uberNeto + $boltNeto + $myPosNeto;
					$ukupnoGotovina = $uberGotovina + $boltGotovina;
					$ukupnoRazlika = $uberRazlika + $boltRazlika + $myPosRazlika;
					$taximetar = 0;
					if($driver['taximetar'] != 0){
						$taximetar = 4;
						$firmaTaximetar += $taximetar;
					}
					$referal_reward = 0;
					
							$refered_by =  $driver['refered_by'];
					if($driver['aktivan'] != 0){
						if($driver['vrsta_provizije'] != 'Postotak'){
							$pocetakRada  = $driver['pocetak_rada'];
							$origin = date_create($en_date);
							$target = date_create($pocetakRada);
							$interval = date_diff($target, $origin);
							$razlika = $interval->format('%R%a');
							$razlika = floatval($razlika);
							
							$pocetakRada = str_replace('-', '', $pocetakRada);
							$razlikaDana = $start_date - $pocetakRada;
							$provizija = $driver['iznos_provizije'] - $driver['popust_na_proviziju'];
							$refered_by =  $driver['refered_by'];
							$referal_reward = $driver['referal_reward'];
							$provizijaPoDanu = $provizija / 7;
							$provizijaPoDanu = round($provizijaPoDanu, 2);
							$referal_po_danu = $referal_reward / 7;
							$referal_po_danu = round($referal_po_danu, 2);
							if($razlika < 7){
								$referal_reward = $razlika * $referal_po_danu;
								$provizija = $razlika * $provizijaPoDanu;
							}
							$firmaProvizija += $provizija;

						}
						else{
							$postotak = (float) $driver['iznos_provizije'] - (float) $driver['popust_na_proviziju'];
							$provizija = ($postotak / 100) * $ukupnoNeto;
							$provizija = round($provizija, 2);
							$referal_reward = $driver['referal_reward'];
							if($referal_reward != 0){
								$referal_reward = $provizija / 3;
								$referal_reward = round($referal_reward, 2);
							}
							$firmaProvizija += $provizija;
						}
					} 
					
					if($referal_reward != 0){
						$ref_bonus = array(
							'vozac_id' => $driver['id'],
							'vozac' => $driver['vozac'],
							'refered_by' => $driver['refered_by'],
							'referal_reward' => $referal_reward,
							'week' => $week,
						);
						$referalBonusModel->insert($ref_bonus);
					}
					
					$fiskalizacija = array(
						'vozac_id' => $driver['id'],
						'vozac' => $driver['vozac'],
						'fleet' => $fleet,
						'month' => $fiskMonthNo,
						'bolt_fiskalizacija' => $boltFiskalizacija,
						'uber_fiskalizacija' => $uberFiskalizacija
					);
					$fiskalizacijaModel->insert($fiskalizacija);
					
					$fiskalizacijaUber = 0;
					$fiskalizacijaBolt = 0;

					if($fiskalizacijaNaplata != false){
						$mjFiskalizacijaBolt = $fiskalizacijaModel->where(['vozac_id' => $driver['id']])->where(['month' => $fiskMonthNo])->where(['bolt_fiskalizacija' => 1])->countAllResults();
						$mjFiskalizacijaUber = $fiskalizacijaModel->where(['vozac_id' => $driver['id']])->where(['month' => $fiskMonthNo])->where(['uber_fiskalizacija' => 1])->countAllResults();
						if($mjFiskalizacijaBolt > 0){
							$fiskalizacijaBolt = 2.12;
							$firmaFiskalizacijaBolt += $fiskalizacijaBolt;
						}
						if($mjFiskalizacijaUber > 0){
							$fiskalizacijaUber = 6.5;
							$firmaFiskalizacijaUber += $fiskalizacijaUber;
						}
						
					}
					
					// OBRACUN DOPRINOSA
					$trosakDop['dop'] = 0;
					$trosakDop['bruto'] = 0;
					$prijava = $driver['prijava'];
					if($prijava != 0){
						$brojSati = $driver['broj_sati'];
						$doprinosi = $doprinosiModel->where('broj_sati', $brojSati)->get()->getRowArray();
						$dateOfBirth = $driver['dob'];
						$dateOfBirth = str_replace('/', '-', $dateOfBirth);
						$today = date("d-m-Y");
						$diff = date_diff(date_create($dateOfBirth), date_create($today));
						$age = $diff->format('%y');
						$trosakDop = array();
						if($age < 30){
							$dop = $doprinosi['dop_do_30'] / 4;
							$dop = round($dop, 2);
							$bruto = $doprinosi['bruto_do_30'] / 4;
							$bruto = round($bruto, 2);
							$trosakDop['dop'] = $dop;
							$trosakDop['bruto'] = $bruto;
						} else{
							$dop = $doprinosi['dop_od_30'] / 4;
							$dop = round($dop, 2);
							$bruto = $doprinosi['bruto_od_30'] / 4;
							$bruto = round($bruto, 2);
							$trosakDop['dop'] = $dop;
							$trosakDop['bruto'] = $bruto;
						}
					}
					
					$obracun2[]= array(
						'vozac_id' => $driver['id'],
						'vozac' => $driver['vozac'],
						'aktivan' => $driver['aktivan'],
						'boltNeto' => $boltNeto,
						'boltGotovina' => $boltGotovina,
						'boltRazlika' => $boltRazlika,
						'uberNeto' => $uberNeto,
						'uberGotovina' => $uberGotovina,
						'uberRazlika' => $uberRazlika,
						'myPosNeto' => $myPosNeto,
						'myPosGotovina' => $myPosGotovina,
						'myPosRazlika' => $myPosRazlika,
						'ukupnoNeto' => $ukupnoNeto,
						'ukupnoGotovina' => $ukupnoGotovina,
						'ukupnoRazlika' => $ukupnoRazlika,
						'provizija' => $provizija,
						'taximetar' => $taximetar,
						'fleet' => $fleet,
						'refered_by' => $refered_by,
						'referal_reward' => $referal_reward,
						'myPosTransakcije' => $myPosTransakcije,
						'week' => $week,
						'doprinosi' => $trosakDop['dop'],
						'bruto_placa' => $trosakDop['bruto'],
						'fiskalizacijaUber' => $fiskalizacijaUber,
						'fiskalizacijaBolt' => $fiskalizacijaBolt,
					);


				}
				$insertObracun = array();
				$firmaBonusi = 0;
				foreach($obracun2 as $obracuni){
					if($obracuni['ukupnoNeto'] != 0 OR $obracuni['aktivan'] != 0){
						$referals_nagrada = $referalBonusModel-> where(['refered_by' => $obracuni['vozac']])-> where(['week' => $week])->get()->getResultArray();
						$ref_nagr = array();
						$bonus_ref = 0;
						foreach($referals_nagrada as $ref){
							$bonus_ref += $ref['referal_reward'];
							$ref_nagr[] = array(
								'refered_vozac' => $ref['vozac'],
								'refered_nagrada' => $ref['referal_reward'],
							);
						}
						$firmaBonusi += $bonus_ref;
						$ref_nagr_json = json_encode($ref_nagr);
						$obracuni['referals'] = $ref_nagr_json;
						$obracuni['bonus_ref'] = $bonus_ref;
					
						$insertObracun[] = $obracuni;
					}
					
				}
//				print_r($insertObracun);
//				die();
				$obracunModel ->insertBatch($insertObracun);

				$firmaProvizija = $firmaProvizija - $firmaBonusi;
				$firmaNeto = $uberNetoSvi + $boltNetoSvi + $myPosNetoSvi;
				$firmaGotovina = $uberGotovinaSvi + $boltGotovinaSvi;
				$firmaRazlika = $firmaNeto + $firmaGotovina;
				$firmaFiskalizacija = $firmaFiskalizacijaBolt + $firmaFiskalizacijaUber;
				$zaIsplatitVozacima = $firmaRazlika - $firmaProvizija - $firmaTaximetar - $firmaFiskalizacija;
				$trebaOstatFirmi = $firmaRazlika - $zaIsplatitVozacima;

				$obracunFirma = array(
						'uberNetoSvi'			=>	$uberNetoSvi,
						'uberGotovinaSvi'		=>	$uberGotovinaSvi,
						'uberRazlikaSvi'		=>	$uberRazlikaSvi,
						'boltNetoSvi'			=>	$boltNetoSvi,
						'boltRazlikaSvi'		=>	$boltRazlikaSvi,
						'boltGotovinaSvi'		=>	$boltGotovinaSvi,
						'myPosNetoSvi'			=>	$myPosNetoSvi,
						'myPosGotovinaSvi'		=>	0,
						'myPosRazlikaSvi'		=>	$myPosNetoSvi,
						'firmaNeto'				=>	$firmaNeto,
						'firmaGotovina'			=>	$firmaGotovina,
						'firmaRazlika'			=>	$firmaRazlika,
						'firmaProvizija'		=>	$firmaProvizija,
						'firmaTaximetar'		=>	$firmaTaximetar,
						'refBonus'				=>	$firmaBonusi,
						'week'					=>	$week,
						'fleet'					=>  $fleet,
						'zaIsplatitVozacima'	=>  $zaIsplatitVozacima,
						'trebaOstatFirmi'		=>  $trebaOstatFirmi,
						'firmaFiskalizacijaBolt'=>  $firmaFiskalizacijaBolt,
						'firmaFiskalizacijaUber'=>  $firmaFiskalizacijaUber,
						'firmaFiskalizacija'	=>  $firmaFiskalizacija
				);
				$obracunFirmaModel -> insert($obracunFirma);
				$data['page'] = 'Obračun';
				$data['obracunFirma'] = $obracunFirma;
				$data['week'] = $week;
				$reportForWeek = array();

				$uberObracun = new UberReportModel();
				$week2 = $uberObracun->select('report_for_week')->where('fleet', $fleet)->get()->getResultArray();
				foreach($week2 as $we){$reportForWeek[] = $we['report_for_week'];}

				$reportForWeek = array_unique($reportForWeek);

				$data['reportForWeek'] = $reportForWeek;
				$data['fleet'] = $fleet;

				return view('adminDashboard/header', $data)
				. view('adminDashboard/navBar')
				. view('adminDashboard/obracun1',$data)
					. view('footer');
			}
			else{
				
				$reportForWeek = array();
				$uberObracun = new UberReportModel();
				$week2 = $uberObracun->select('report_for_week')->where('fleet', $fleet)->get()->getResultArray();
				foreach($week2 as $we){$reportForWeek[] = $we['report_for_week'];}
				$reportForWeek = array_unique($reportForWeek);
				
				$obracun = $obracunModel->where(['week' => $week])->where('fleet', $fleet)->get()->getResultArray();
				$obracunFirma = $obracunFirmaModel->where(['week' => $week])->where('fleet', $fleet)->get()->getRowArray();
				$voCount = 0;
				$sviNeto = 0;
				$sviProvizija = 0;
				foreach($obracun as $obr){
					if($obr['ukupnoNeto'] > 0){
						$voCount += 1;
						$sviNeto += $obr['ukupnoNeto'];
						$sviProvizija += $obr['provizija'];
					}

				}
				
				$nemaWhatsApp = array();
				$poslatiSlike = array();
				$DriverModel = new DriverModel();
				$UltramsgLib = new UltramsgLib();
				
				
				foreach($obracun as $obr){
				$directoryPath =  base_url($obr['slikaObracuna']);
				//$directoryPath = FCPATH . $obr['slikaObracuna'];

					$iznos = 0;
					$iznos = $obr['ukupnoNeto'] + $obr['zaIsplatu'];
					if($iznos != 0){
						$driver = $DriverModel->where('id', $obr['vozac_id'])->get()->getRowArray();
						if(!empty($driver['whatsApp'])){
							$mobitel = $driver['whatsApp'];
						}else{
							$mobitel = $driver['mobitel'];
						}
							

							$poslatiSlike[] = array(
								'id' => $obr['id'],
								'mobitel' => $mobitel,
								'slika_url' => $directoryPath,
								'raspon' => $obr['raspon'],
								'vozac' => $obr['vozac'],
							);

						
					}
					
				}

				$netoPoVozacu = $sviNeto / $voCount;
				$provizijaPoVozacu = $sviProvizija / $voCount;
				$brutoPoVozacu = $netoPoVozacu / 0.75;
				$gorivoPoVozacu = $netoPoVozacu * 0.20;

				$stats = array(
					'brojVozaca' => $voCount,
					'sviNeto' => $sviNeto,
					'sviProvizija' => $sviProvizija,
					'provizijaPoVozacu' => $provizijaPoVozacu,
					'netoPoVozacu' => $netoPoVozacu,
					'brutoPoVozacu' => $brutoPoVozacu,
					'gorivoPoVozacu' => $gorivoPoVozacu
				);
				
				$poslatiSlikeJson = json_encode($poslatiSlike);

				$data['poslatiSlikeJson'] = $poslatiSlikeJson;
				$data['poslatiSlike'] = $poslatiSlike;
				$data['nemaWhatsApp'] = $nemaWhatsApp;
				$data['stats'] = $stats;
				$data['obracun'] = $obracun;
				$data['obracunFirma'] = $obracunFirma;
				$data['page'] = 'Obračun';
				$data['reportForWeek'] = $reportForWeek;
				$data['week'] = $week;
				$data['fleet'] = $fleet;

				return view('adminDashboard/header', $data)
				. view('adminDashboard/navBar')
				. view('adminDashboard/obracun1')
					. view('footer');
			}
		}
		
		else{
			$reportForWeek = array();
			$uberObracun = new UberReportModel();
			$week2 = $uberObracun->select('report_for_week')->where('fleet', $fleet)->get()->getResultArray();
			foreach($week2 as $we){$reportForWeek[] = $we['report_for_week'];}
			$reportForWeek = array_unique($reportForWeek);
			
			$data['reportForWeek'] = $reportForWeek;
			$data['page'] = 'Obračun';
			$data['fleet'] = $fleet;
			return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/obracun1',$data)
				. view('footer');
		}
	}
	
	
	public function poslatiSlike(){
		$poslatiSlikeJson = $this->request->getVar('poslatiSlikeJson');
		$poslatiSlike = json_decode($poslatiSlikeJson, true);
		$UltramsgLib = new UltramsgLib();
		$count = 0;
		$result = array();
//		print_r($poslatiSlike);
//		die();
		foreach($poslatiSlike as $obracun){
			$raspon = $obracun['raspon'];
			$data['to'] = $obracun['mobitel'];
			$data['image'] = $obracun['slika_url'];
			$data['caption'] = "Ovo je tvoj obračun za period $raspon . Ovo je automatski generirana i poslana poruka. Ukoliko smatraš da si dobio/la krive podatke slobodno nam se obrati ";
			$status = $UltramsgLib->sendImg($data);

			$count +=1;
			$result[] = $status;
			
		}
		
		$data['count'] = $count;
		$data['result'] = $result;
		
		return $data;
	}
	
	public function poslatiObracun(){
		$session = session();
		$DriverModel = new DriverModel();
		$UltramsgLib = new UltramsgLib();
		$obracunModel = new ObracunModel();
		$obracunID = $this->request->getVar('id');
		$obr = $obracunModel->where('id', $obracunID)->get()->getRowArray();
		$week = $obr['week'];
		$driver = $DriverModel->where('id', $obr['vozac_id'])->get()->getRowArray();
		if(!empty($driver['whatsApp'])){
			$mobitel = $driver['whatsApp'];
		}else{
			$mobitel = $driver['mobitel'];
		}
		
		$directoryPath =  base_url($obr['slikaObracuna']);
		
		$raspon = $obr['raspon'];
		$data['to'] = $mobitel;
		$data['image'] = $directoryPath;
		$data['caption'] = "Ovo je tvoj obračun za period $raspon . Ovo je automatski generirana i poslana poruka. Ukoliko smatraš da si dobio/la krive podatke slobodno nam se obrati ";
		
		$status = $UltramsgLib->sendImg($data);
		if(isset($status['error'])){
			$session->setFlashdata('msgSlika', ' Obračun nije poslan.');
			session()->setFlashdata('alert-class', 'alert-danger');

		}else{
			$vozac = $driver['vozac'];
			$session->setFlashdata('msgSlika', " Obračun vozaču $vozac za period $raspon je poslan.");
			session()->setFlashdata('alert-class', 'alert-success');
		}

//		print_r($status);
//		die();
		return redirect()->to(base_url("index.php/obracun/$week#$obracunID"));
		
	}
	
	function getLast5WeeksExcludingCurrent() {
    $weeks = [];

    // Get the current date and time
    $currentDate = new \DateTime();

    // Get the current week
    $currentWeek = $currentDate->format('W');

    // Loop through the last 5 weeks (excluding the current week)
    for ($i = 1; $i <= 5; $i++) {
        // Get the year and week number
        $year = $currentDate->format('Y');
        $week = $currentWeek - $i;

        // Handle the case where the week goes below 1 (previous year)
        if ($week < 1) {
            $year--;
            $week = 52 + $week;
        }

        // Combine year and week number and add to the array
        $weeks[] = $year . str_pad($week, 2, '0', STR_PAD_LEFT);
    }

    return array_reverse($weeks);
}
	
	public function obracunNajma($id = null){
		
		$session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$obracunModel = new ObracunModel();
		$vozilaModel = new VozilaModel();
		$driversModel = new DriverModel();
		$tvrtkaModel = new TvrtkaModel();
		
		$tvrtka = $tvrtkaModel->where('fleet', $fleet)->get()->getRowArray();
		$obracun = $obracunModel->where('id', $id)->get()->getRowArray();
		$driver = $driversModel->where('id', $obracun['vozac_id'])->get()->getRowArray();
		$vozilo = $vozilaModel->where('vozac_id', $driver['id'])->get()->getRowArray();
		$week = $obracun['week'];
		
		$yearNo = substr($week, 0, 4);
		$weekNo = substr($week, 4, 2);

		$dateTime = new \DateTime();
		$dateTime->setISODate($yearNo, $weekNo);
		$result['start_date'] = $dateTime->format('Ymd');
		$result['weekStartMonth'] = $dateTime->format('m');

		$dateTime->modify('+6 days');
		$result['end_date'] = $dateTime->format('Ymd');
		$result['weekEndMonth'] = $dateTime->format('m');
		$dateTime->setISODate($yearNo, $weekNo);

		$dateTime->modify('+9 days');
		$result['dan_isplate'] = $dateTime->format('Ymd');
		$dateTime->modify('-2 days');
		$result['dan_obracuna'] = $dateTime->format('d.m.Y.');
		
		$danObracuna = $result['dan_obracuna'];
		$danIsplate = $result['dan_isplate'];
		$start_date = $result['start_date'];
		$end_date = $result['end_date'];
		$prviDan = substr($start_date, 6, 2).'.'.substr($start_date, 4, 2).'.'.substr($start_date, 0, 4).'.';
		$zadnjiDan = substr($end_date, 6, 2).'.'.substr($end_date, 4, 2).'.'.substr($end_date, 0, 4).'.';
		$danIsplate = substr($danIsplate, 6, 2).'.'.substr($danIsplate, 4, 2).'.'.substr($danIsplate, 0, 4).'.';
		
		$neto = $obracun['ukupnoNeto'];
		$vozac = $obracun['vozac'];

		$osnovnaCijena = $vozilo['cijena_tjedno'];
		$cijenaPoKm = $vozilo['cijena_po_km'];
		$ukljuceniKm = $osnovnaCijena / $cijenaPoKm;
		$ukljuceniKm = round($ukljuceniKm, 0);

		$prijedeniKm = $neto / $cijenaPoKm;
		$prijedeniKm = round($prijedeniKm, 0);

		if($prijedeniKm > $ukljuceniKm){
			$kmZaDoplatit = $prijedeniKm - $ukljuceniKm;
			$dug = $kmZaDoplatit * $cijenaPoKm;

		}else{

			$dug = 0;
		}
		
		$data['tvrtka'] = $tvrtka;
		$data['page'] = 'obracun najma';
		$data['knjigoReport'] = array(
			'proizvodac' 		=> $vozilo['proizvodac'], 
			'model' 			=> $vozilo['model'], 
			'reg'  				=> $vozilo['reg'],
			'vozac'				=> $vozac,
			'osnovnaCijena'  	=> $osnovnaCijena,
			'cijenaPoKm'  		=> $cijenaPoKm,
			'ukljuceniKm'  		=> $ukljuceniKm,
			'prijedeniKm'  		=> $prijedeniKm,
			'dug' 				=> $dug,
			'obrRazdobljeOd'  	=> $prviDan,
			'obrRazdobljeDo' 	=> $zadnjiDan,
			'danIsplate'  		=> $danIsplate,
			'danObracuna'  		=> $danObracuna,


		);
		
		return view('adminDashboard/header', $data)
			.view('adminDashboard/placanjeNajam');


	}
	
	public function sendTestEmail()
    {
        $email = \Config\Services::email(); // Get the email service

        $email->setTo('<nikola.sagi@gmail.com>'); // Set the recipient
        $email->setSubject('Test Email from CodeIgniter 4'); // Email subject
        $email->setMessage('This is a test email from your CodeIgniter 4 application.'); // Email message

        if ($email->send()) {
            echo 'Email sent successfully!';
        } else {
            echo 'Failed to send email.';
            // If there's an error, output the error message
            echo $email->printDebugger(['headers']);
        }
    }
	
public function fetchDriverData()
{
    // Check if vozac_id is provided in the GET parameters
    $vozac_id = $this->request->getGet('vozac_id');

    if ($vozac_id) {
        // Load the DriverModel and PrijaveModel
        $driverModel = new DriverModel();
        $prijaveModel = new PrijaveModel();

        // Perform a subquery to select the latest timestamp for each vozac_id
        $subQuery = $prijaveModel->select('MAX(timestamp) as latest_timestamp')
                                 ->where('vozac_id', $vozac_id)
                                 ->groupBy('vozac_id');

        // Join the vozaci table with the subquery to get the latest entry from prijaveModel
        $query = $driverModel->select('vozaci.*, prijave.prekid_rada, prijave.id, prijave.vozac_id')
                     ->join('prijave', 'prijave.vozac_id = vozaci.id AND prijave.timestamp = (SELECT MAX(timestamp) FROM prijave WHERE vozac_id = vozaci.id)', 'left')
                     ->where('vozaci.id', $vozac_id)
                     ->get();

        // Fetch the result row
        $driverData = $query->getRowArray();

        // Return data as JSON
        return $this->response->setJSON($driverData);
    } else {
        // If vozac_id is not provided, return an error response
        return $this->response->setStatusCode(400)->setJSON(['error' => 'Vozac ID not provided']);
    }
}	
	
	
}
