<?php
namespace App\Controllers;
require_once FCPATH . '../vendor/autoload.php';
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
//MODELS
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
use App\Models\ActivityUberModel;
use App\Models\BoltDriverActivityModel;
//SERVICES
use App\Services\ActivityCalendarService;

class VoditeljController extends BaseController
{

	public function index(){
		
		$session = session();
		$fleet = $session->get('fleet');
		$data = $session->get();
		$data['page'] = 'Dashboard';
		$boltActivityModel = new BoltDriverActivityModel();
		$uberActivityModel = new ActivityUberModel();

		// Fetch data from the database
		$uberActivity = $uberActivityModel->select('datum_unosa')
										  ->where('fleet', $fleet)
										  ->get()->getResultArray();

		$boltActivity = $boltActivityModel->select('start_date')
										  ->where('fleet', $fleet)
										  ->get()->getResultArray();

		$uberDates = array_column($uberActivity, 'datum_unosa');

		// Fetch dates from $boltActivity
		$boltDates = array_column($boltActivity, 'start_date');
		$uberDates = array_unique($uberDates);

		// Fetch dates from $boltActivity
		$boltDates = array_unique($boltDates);
		$data['boltDates'] = $boltDates;
		$data['uberDates'] = $uberDates;
		
//		print_r($boltDates);
//		die();

        return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/putNovca')		
			. view('adminDashboard/voditelj')		
			. view('footer');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}