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

class VoditeljController extends BaseController
{

	public function index(){
		
		$session = session();
		$fleet = $session->get('fleet');
		$data = $session->get();
		$data['page'] = 'Dashboard';

        return view('adminDashboard/header', $data)
			. view('adminDashboard/navBar')
			. view('adminDashboard/voditelj')		
			. view('adminDashboard/putNovca')		
			. view('footer');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}