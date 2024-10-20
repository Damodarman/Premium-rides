<?php

namespace App\Controllers;
require_once FCPATH . '../vendor/autoload.php';
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\UberReportModel;
use App\Models\BoltReportModel;
use App\Models\MyPosReportModel;
use App\Models\DriverModel;
use App\Models\FlotaModel;
use App\Models\TvrtkaModel;
use App\Models\TaximetarReportModel;
use CodeIgniter\Files\File;
use App\Models\ActivityUberModel;
use App\Models\BoltDriverActivityModel;

use Twilio\Rest\Client;
use App\Libraries\UltramsgLib;

class NaPlacuController extends BaseController
{

    public function __construct()
    {
    }
	
	public function index()
	{
        $session = session();
		$fleet = $session->get('fleet');
		$data = $session->get();
		$driversFind = new DriverModel();
		$drivers = $driversFind->where('fleet', $fleet)->where('nacin_rada', 'placa')->where('aktivan', 1)->get()->getResultArray();
		$driversCount = $driversFind->where('fleet', $fleet)->where('nacin_rada', 'placa')->where('aktivan', 1)->countAllResults(); 
		$activity = $this->driverHours($drivers);
		$data['page'] = 'Vozači na plaču';
		$data['drivers'] = $drivers;
		$data['fleet'] = $fleet;
		$data['driversCount'] = $driversCount;
		$data['activity'] = $activity;
		
		
		
		echo view('adminDashboard/header', $data)
			.view('adminDashboard/navBar')
			.view('adminDashboard/naPlacu')
			.view('footer');
	}
	
	public function driverHours($drivers){
		$dates = $this->getDatesForHours();
			    $boltActivityModel = new BoltDriverActivityModel();
				$uberActivityModel = new ActivityUberModel();
			$ubThisWeekOnlin = 0;
			$ubThisWeekActiv = 0;
			$boThisWeekOnlin = 0;
			$boThisWeekActiv = 0;
			$ubLastWeekOnlin = 0;
			$ubLastWeekActiv = 0;
			$boLastWeekOnlin = 0;
			$boLastWeekActiv = 0;
			$ubThisMonthOnlin = 0;
			$ubThisMonthActiv = 0;
			$boThisMonthOnlin = 0;
			$boThisMonthActiv = 0;
			$ubLastMonthOnlin = 0;
			$ubLastMonthActiv = 0;
			$boLastMonthOnlin = 0;
			$boLastMonthActiv = 0;
		$activity = array();
		foreach($drivers as $driver){
			$ubThisWeekOnlin = 0;
			$ubThisWeekActiv = 0;
			$boThisWeekOnlin = 0;
			$boThisWeekActiv = 0;
			$ubLastWeekOnlin = 0;
			$ubLastWeekActiv = 0;
			$boLastWeekOnlin = 0;
			$boLastWeekActiv = 0;
			$ubThisMonthOnlin = 0;
			$ubThisMonthActiv = 0;
			$boThisMonthOnlin = 0;
			$boThisMonthActiv = 0;
			$ubLastMonthOnlin = 0;
			$ubLastMonthActiv = 0;
			$boLastMonthOnlin = 0;
			$boLastMonthActiv = 0;

			// THIS WEEK
			$uberActivityThisWeek = $uberActivityModel->where('vozac', $driver['uber_unique_id'])
											  ->where('datum_unosa >=', $dates['thisWeekStart']) // Here might be the issue
											  ->where('datum_unosa <=', $dates['thisWeekEnd'])   // And here as well
											  ->get()->getResultArray();
			foreach($uberActivityThisWeek as $act){
				$ubThisWeekOnlin += $act['vrijeme_na_mrezi'];
				$ubThisWeekActiv += $act['vrijeme_voznje'];
			}

			$boltActivityThisWeek = $boltActivityModel->where('driver_name', $driver['bolt_unique_id'])
											  ->where('start_date >=', $dates['thisWeekStart']) // Same here
											  ->where('start_date <=', $dates['thisWeekEnd'])   // Same here
											  ->get()->getResultArray();
			foreach($boltActivityThisWeek as $act){
				$ubThisWeekOnlin += $act['online_hours'];
				$ubThisWeekActiv += $act['active_driving_hours'];
			}
			// LAST WEEK
			$uberActivityLastWeek = $uberActivityModel->where('vozac', $driver['uber_unique_id'])
											  ->where('datum_unosa >=', $dates['lastWeekStart']) // Here might be the issue
											  ->where('datum_unosa <=', $dates['lastWeekEnd'])   // And here as well
											  ->get()->getResultArray();
			foreach($uberActivityLastWeek as $act){
				$ubLastWeekOnlin += $act['vrijeme_na_mrezi'];
				$ubLastWeekActiv += $act['vrijeme_voznje'];
			}

			$boltActivityLastWeek = $boltActivityModel->where('driver_name', $driver['bolt_unique_id'])
											  ->where('start_date >=', $dates['lastWeekStart']) // Same here
											  ->where('start_date <=', $dates['lastWeekEnd'])   // Same here
											  ->get()->getResultArray();
			foreach($boltActivityLastWeek as $act){
				$ubLastWeekOnlin += $act['online_hours'];
				$ubLastWeekActiv += $act['active_driving_hours'];
			}
			// THIS MONTH
			$uberActivityThisMonth = $uberActivityModel->where('vozac', $driver['uber_unique_id'])
											  ->where('datum_unosa >=', $dates['thisMonthStart']) // Here might be the issue
											  ->where('datum_unosa <=', $dates['thisMonthEnd'])   // And here as well
											  ->get()->getResultArray();
			foreach($uberActivityThisMonth as $act){
				$ubThisMonthOnlin += $act['vrijeme_na_mrezi'];
				$ubThisMonthActiv += $act['vrijeme_voznje'];
			}

			$boltActivityThisMonth = $boltActivityModel->where('driver_name', $driver['bolt_unique_id'])
											  ->where('start_date >=', $dates['thisMonthStart']) // Same here
											  ->where('start_date <=', $dates['thisMonthEnd'])   // Same here
											  ->get()->getResultArray();
			foreach($boltActivityThisMonth as $act){
				$ubThisMonthOnlin += $act['online_hours'];
				$ubThisMonthActiv += $act['active_driving_hours'];
			}
			// LAST MONTH
			$uberActivityLastMonth = $uberActivityModel->where('vozac', $driver['uber_unique_id'])
											  ->where('datum_unosa >=', $dates['lastMonthStart']) // Here might be the issue
											  ->where('datum_unosa <=', $dates['lastMonthEnd'])   // And here as well
											  ->get()->getResultArray();
			foreach($uberActivityLastMonth as $act){
				$ubLastMonthOnlin += $act['vrijeme_na_mrezi'];
				$ubLastMonthActiv += $act['vrijeme_voznje'];
			}

			$boltActivityLastMonth = $boltActivityModel->where('driver_name', $driver['bolt_unique_id'])
											  ->where('start_date >=', $dates['lastMonthStart']) // Same here
											  ->where('start_date <=', $dates['lastMonthEnd'])   // Same here
											  ->get()->getResultArray();
			foreach($boltActivityLastMonth as $act){
				$ubLastMonthOnlin += $act['online_hours'];
				$ubLastMonthActiv += $act['active_driving_hours'];
			}
			
			$activity[] = array(
				'id' => $driver['id'],
				'vozac' => $driver['vozac'],
				'id' 			   => $driver['id'],
				'ubThisWeekOnlin'  =>$ubThisWeekOnlin,
				'ubThisWeekActiv'  =>$ubThisWeekActiv,
				'ubLastWeekOnlin'  =>$ubLastWeekOnlin,
				'ubLastWeekActiv'  =>$ubLastWeekActiv,
				'ubThisMonthOnlin' =>$ubThisMonthOnlin,
				'ubThisMonthActiv' =>$ubThisMonthActiv,
				'ubLastMonthOnlin' =>$ubLastMonthOnlin,
				'ubLastMonthActiv' =>$ubLastMonthActiv,
				
			);
		}
		
				return $activity;
	}
	
	public function getDatesForHours(){
		helper('date');
		$now = now(); 
		// Current Week
		$thisWeekStart = strtotime('monday this week', $now); 
		$thisWeekEnd = strtotime('sunday this week', $now);

		// Last Week
		$lastWeekStart = strtotime('monday last week', $now);
		$lastWeekEnd = strtotime('sunday last week', $now);

		// Current Month
		$thisMonthStart = strtotime('first day of this month', $now);
		$thisMonthEnd = strtotime('last day of this month', $now);

		// Last Month
		$lastMonthStart = strtotime('first day of last month', $now);
		$lastMonthEnd = strtotime('last day of last month', $now);
		
		$data['thisWeekStart'] = date('Y-m-d', $thisWeekStart);
		$data['thisWeekEnd'] = date('Y-m-d', $thisWeekEnd);
		$data['lastWeekStart'] = date('Y-m-d', $lastWeekStart);
		$data['lastWeekEnd'] = date('Y-m-d', $lastWeekEnd);
		$data['thisMonthStart'] = date('Y-m-d', $thisMonthStart);
		$data['thisMonthEnd'] = date('Y-m-d', $thisMonthEnd);
		$data['lastMonthStart'] = date('Y-m-d', $lastMonthStart);
		$data['lastMonthEnd'] = date('Y-m-d', $lastMonthEnd);
		
		return $data;
	}
	
}