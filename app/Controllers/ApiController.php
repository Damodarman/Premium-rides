<?php

namespace App\Controllers;

use App\Models\VehicleModel;
use App\Models\DriverModel;
use App\Models\ObracunFirmaModel;

use App\Models\UberReportModel;
use App\Models\TaximetarReportModel;
use App\Models\MyPosReportModel;
use App\Models\BoltReportModel;


class ApiController extends BaseController
{
    protected $vehicleModel;
    protected $driverModel;
    protected $obracunFirmaModel;

    public function __construct()
    {
        $this->vehicleModel = new VehicleModel();
        $this->driverModel = new DriverModel();
        $this->obracunFirmaModel = new ObracunFirmaModel();
    }
	
	public function checkSession()
{
    $loggedIn = session()->has('id'); // Check if user session exists
    return $this->response->setJSON(['loggedIn' => $loggedIn]);
}

    public function getVehicleCounts()
    {
        // Check if the request is AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Access forbidden: AJAX requests only']);
        }

        // Fetch the vehicle counts
        $session = session();
        $fleet = $session->get('fleet');
        $operatingVehiclesCount = $this->vehicleModel->getActiveVehiclesCount($fleet);
        $nonWorkingVehiclesCount = $this->vehicleModel->getInactiveVehiclesCount($fleet);
        $allVehiclesCount = $this->vehicleModel->getAllVehiclesCount($fleet); // Ensure this method exists

        // Return the counts as a JSON response
        return $this->response->setJSON([
            'operatingVehiclesCount' => $operatingVehiclesCount,
            'nonWorkingVehiclesCount' => $nonWorkingVehiclesCount,
            'allVehiclesCount' => $allVehiclesCount,
        ]);
    }
    public function getDriversCounts()
    {
        // Check if the request is AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Access forbidden: AJAX requests only']);
        }

        // Fetch the vehicle counts
        $session = session();
        $fleet = $session->get('fleet');
        $operatingDriversCount = $this->driverModel->getActiveDriversCount($fleet);
        $nonWorkingDriversCount = $this->driverModel->getInactiveDriversCount($fleet);
        $allDriversCount = $this->driverModel->getAllDriversCount($fleet); // Ensure this method exists

        // Return the counts as a JSON response
        return $this->response->setJSON([
            'operatingDriversCount' => $operatingDriversCount,
            'nonWorkingDriversCount' => $nonWorkingDriversCount,
            'allDriversCount' => $allDriversCount,
        ]);
    }
	
    public function getPlatformRatio() {
        // Ensure the request is AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Access forbidden: AJAX requests only']);
        }

        // Get the fleet from session
        $session = session();
        $fleet = $session->get('fleet');

        // Get the optional filter for weeks (if provided)
        $startWeek = $this->request->getPost('startWeek');
        $endWeek = $this->request->getPost('endWeek');

        // Prepare the filter array
        $filters = [];
        if ($startWeek && $endWeek) {
            $filters['startWeek'] = $startWeek;
            $filters['endWeek'] = $endWeek;
        }

        // Fetch the data from the model using the filters (if provided)
        $data = $this->obracunFirmaModel->platformRatio($fleet, $filters);

        // Return the data and available weeks as JSON response
		return $this->response->setJSON($data);
		
    }
	
	public function getReportWeeks(){
        $session = session();
        $fleet = $session->get('fleet');
		$UberReportModel = new UberReportModel();
		$BoltReportModel = new BoltReportModel();
		$TaximetarReportModel = new TaximetarReportModel();
		$MyPosReportModel = new MyPosReportModel();
		// Fetch distinct weeks from each table
		$uberWeeks = $UberReportModel->where('fleet', $fleet)->distinct()->select('report_for_week')->findAll();
		$boltWeeks = $BoltReportModel->where('fleet', $fleet)->distinct()->select('report_for_week')->findAll();
		$taximetarWeeks = $TaximetarReportModel->where('fleet', $fleet)->distinct()->select('week')->findAll();
		$myPosWeeks = $MyPosReportModel->where('fleet', $fleet)->distinct()->select('report_for_week')->findAll();

		// Extract weeks into arrays
		$weeks = array_merge(
			array_column($uberWeeks, 'report_for_week'),
			array_column($boltWeeks, 'report_for_week'),
			array_column($taximetarWeeks, 'week'),
			array_column($myPosWeeks, 'report_for_week')
		);

		// Return unique weeks sorted in ascending order
		$uniqueWeeks = array_unique($weeks);
		sort($uniqueWeeks);

		return $this->response->setJSON(['uniqueWeeks' => $uniqueWeeks]);
	
	}
	
	public function getDriverNameById()
	{
		if (!$this->request->isAJAX()) {
			return $this->response->setStatusCode(403)->setJSON(['error' => 'Access forbidden: AJAX requests only']);
		}

		// Decode JSON input
		$jsonInput = $this->request->getJSON();
		if (!$jsonInput || !isset($jsonInput->driverId)) {
			return $this->response->setJSON(['error' => 'Invalid input'])->setStatusCode(400);
		}

		$id = $jsonInput->driverId; // Retrieve driver ID

		$driverName = $this->driverModel->getNameById($id);
		return $this->response->setJSON([
			'driverName' => $driverName,
		]);
	}	
	
	public function getDriversReports()
	{
		if (!$this->request->isAJAX()) {
			return $this->response->setStatusCode(403)->setJSON(['error' => 'Access forbidden: AJAX requests only']);
		}
        $session = session();
        $fleet = $session->get('fleet');
		$UberReportModel = new UberReportModel();
		$BoltReportModel = new BoltReportModel();
		$TaximetarReportModel = new TaximetarReportModel();
		$MyPosReportModel = new MyPosReportModel();

		// Retrieve data from the request
		$startWeek = $this->request->getPost('startWeek');
		$endWeek = $this->request->getPost('endWeek');
		$boltUniqueId = $this->request->getPost('boltUniqueId');
		$uberUniqueId = $this->request->getPost('uberUniqueId');
		$taximetarUniqueId = $this->request->getPost('taximetarUniqueId');
		$myPosUniqueId = $this->request->getPost('myPosUniqueId');

		$uberReports = $UberReportModel->getDriverReports($startWeek, $endWeek, $uberUniqueId, $fleet);
		$boltReports = $BoltReportModel->getDriverReports($startWeek, $endWeek, $boltUniqueId, $fleet);
		$taximetarReports = $TaximetarReportModel->getDriverReports($startWeek, $endWeek, $taximetarUniqueId, $fleet);
		$myPosReports = $MyPosReportModel->getDriverReports($startWeek, $endWeek, $myPosUniqueId, $fleet);
		// Return the same data as a JSON response for testing
		return $this->response->setJSON([
			'uberReports' => $uberReports,
			'boltReports' => $boltReports,
			'taximetarReports' => $taximetarReports,
			'myPosReports' => $myPosReports
		]);
		


	}
}
