<?php

namespace App\Controllers;

use App\Models\VehicleModel;
use App\Models\DriverModel;
use App\Models\ObracunFirmaModel;

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
}
