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

class NaProvizijuController extends BaseController
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
		$drivers = $driversFind->where('fleet', $fleet)->where('nacin_rada', 'provizija')->where('aktivan', 1)->get()->getResultArray();
		$driversCount = $driversFind->where('fleet', $fleet)->where('nacin_rada', 'provizija')->where('aktivan', 1)->countAllResults(); 
		
		$cache = \Config\Services::cache(); // Get the cache instance

		$cacheKey = 'driver_activity_data_' . preg_replace('/\s+/', '_', $fleet);  
		$activity = $cache->get($cacheKey);

		// Check for refreshCache POST data (no CSRF check needed here)
		if ($this->request->getMethod() === 'post' && $this->request->getPost('refreshCache') == 1) {
			$cache->delete($cacheKey); 
		}

		$cacheMetaData = $cache->getMetaData($cacheKey); // Get metadata first

		if ($activity === null || ($cacheMetaData && $cacheMetaData['expire'] < time())) { 
			// Cache is missing OR exists but has expired, recalculate and cache
			$activity = $this->driverHours($drivers);
			$cache->save($cacheKey, $activity, 21600);
			$cache->save('cache_creation_time_' . $cacheKey, time(), 21600);  // Update creation time when cache is refreshed.
		}

		$cacheCreationTime = $cache->get('cache_creation_time_' . $cacheKey);

		if ($cacheCreationTime) {
			$timeDiff = time() - $cacheCreationTime; // Calculate time difference in seconds

			// Convert seconds to a human-readable format
			$data['cacheCreationTime'] = $this->formatTimeElapsed($timeDiff);
		} else {
			$cache->save('cache_creation_time_' . $cacheKey, time(), 21600);
			$data['cacheCreationTime'] = 'Just now';  
		}
		

			//$activity = $this->driverHours($drivers);
			$data['page'] = 'Vozači na proviziju';
			$data['drivers'] = $drivers;
			$data['fleet'] = $fleet;
			$data['driversCount'] = $driversCount;
			$data['activity'] = $activity;



			echo view('adminDashboard/header', $data)
				.view('adminDashboard/navBar')
				.view('adminDashboard/naProviziju')
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

			// LAST WEEK
			$uberActivityLastWeek = $uberActivityModel->where('vozac', $driver['uber_unique_id'])
											  ->where('datum_unosa >=', $dates['lastWeekStart']) // Here might be the issue
											  ->where('datum_unosa <=', $dates['lastWeekEnd'])   // And here as well
											  ->get()->getResultArray();
			foreach($uberActivityLastWeek as $act){
				$ubLastWeekActiv += $act['vrijeme_voznje'];
			}

			$boltActivityLastWeek = $boltActivityModel->where('driver_name', $driver['bolt_unique_id'])
											  ->where('start_date >=', $dates['lastWeekStart']) // Same here
											  ->where('start_date <=', $dates['lastWeekEnd'])   // Same here
											  ->get()->getResultArray();
			foreach($boltActivityLastWeek as $act){
				$ubLastWeekActiv += $act['active_driving_hours'];
			}
			// THIS MONTH
			$uberActivityThisMonth = $uberActivityModel->where('vozac', $driver['uber_unique_id'])
											  ->where('datum_unosa >=', $dates['thisMonthStart']) // Here might be the issue
											  ->where('datum_unosa <=', $dates['thisMonthEnd'])   // And here as well
											  ->get()->getResultArray();
			foreach($uberActivityThisMonth as $act){
				$ubThisMonthActiv += $act['vrijeme_voznje'];
			}

			$boltActivityThisMonth = $boltActivityModel->where('driver_name', $driver['bolt_unique_id'])
											  ->where('start_date >=', $dates['thisMonthStart']) // Same here
											  ->where('start_date <=', $dates['thisMonthEnd'])   // Same here
											  ->get()->getResultArray();
			foreach($boltActivityThisMonth as $act){
				$ubThisMonthActiv += $act['active_driving_hours'];
			}
			// LAST MONTH
			$uberActivityLastMonth = $uberActivityModel->where('vozac', $driver['uber_unique_id'])
											  ->where('datum_unosa >=', $dates['lastMonthStart']) // Here might be the issue
											  ->where('datum_unosa <=', $dates['lastMonthEnd'])   // And here as well
											  ->get()->getResultArray();
			foreach($uberActivityLastMonth as $act){
				$ubLastMonthActiv += $act['vrijeme_voznje'];
			}

			$boltActivityLastMonth = $boltActivityModel->where('driver_name', $driver['bolt_unique_id'])
											  ->where('start_date >=', $dates['lastMonthStart']) // Same here
											  ->where('start_date <=', $dates['lastMonthEnd'])   // Same here
											  ->get()->getResultArray();
			foreach($boltActivityLastMonth as $act){
				$ubLastMonthActiv += $act['active_driving_hours'];
			}
			$sati = $driver['broj_sati'] * 21;
			$satiWeek = $driver['broj_sati'] * 5;
			$activity[] = array(
				'id' => $driver['id'],
				'vozac' => $driver['vozac'],
				'satiMonth' => $sati,
				'satiWeek' => $satiWeek,
				'prijava' => $driver['broj_sati'],
				'id' 			   => $driver['id'],
				'ubLastWeekActiv'  =>$ubLastWeekActiv,
				'ubThisMonthActiv' =>$ubThisMonthActiv,
				'ubLastMonthActiv' =>$ubLastMonthActiv,
				
			);
		}
		
				return $activity;
	}
	
	private function formatTimeElapsed($seconds) 
{
    $minutes = floor($seconds / 60);
    $hours = floor($minutes / 60);
    $days = floor($hours / 24);

    if ($days > 0) {
        return "prije {$days} dan" . ($days == 1 ? '' : 'a');
    } elseif ($hours > 0) {
        return "prije {$hours} sat" . ($hours == 1 ? '' : 'a');
    } elseif ($minutes > 0) {
        return "prije {$minutes} minut" . ($minutes == 1 ? 'u' : 'a');
    } else {
        return 'upravo sada'; // Changed to "Upravo sada" (Just now)
    }
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