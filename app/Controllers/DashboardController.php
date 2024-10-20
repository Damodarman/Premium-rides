<?php

namespace App\Controllers;

use App\Models\VehicleModel;
use App\Models\DriverModel;

class DashboardController extends BaseController
{
    protected $vehicleModel;
    protected $driverModel;

    public function __construct()
    {
        $this->vehicleModel = new VehicleModel();
        $this->driverModel = new DriverModel();
    }

    public function index()
    {
        $session = session();
		$fleet = $session->get('fleet');
        // Fetch data for the dashboard
        $operatingVehiclesCount = $this->vehicleModel->getActiveVehiclesCount($fleet);
        $nonWorkingVehiclesCount = $this->vehicleModel->getInactiveVehiclesCount($fleet);
        $allVehiclesCount = $this->vehicleModel->getInactiveVehiclesCount($fleet);
        $activeDriversCount = $this->driverModel->getActiveDriversCount($fleet);
        $inactiveDriversCount = $this->driverModel->getInactiveDriversCount($fleet);

		// Pass data to the dashboard view
		return view('VehicleManagement/dashboard', [
			'title' => 'Dashboard',
			'activeDriversCount' => $activeDriversCount,
			'operatingVehiclesCount' => $operatingVehiclesCount,
			'inactiveDriversCount' => $inactiveDriversCount,
			'nonWorkingVehiclesCount' => $nonWorkingVehiclesCount,
			'allVehiclesCount' => $allVehiclesCount
		]);
    }
}
