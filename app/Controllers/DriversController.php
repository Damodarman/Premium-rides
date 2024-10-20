<?php

namespace App\Controllers;

use App\Models\DriverModel;
use App\Models\ObracunModel;
use CodeIgniter\Controller;



class DriversController extends Controller
{
    protected $driverModel;
    protected $obracunModel;

    public function __construct()
    {
        $this->driverModel = new DriverModel();
        $this->obracunModel = new ObracunModel();
    }

    // Fetch and display all drivers by fleet
    public function index()
    {
        $session = session();
		$fleet = $session->get('fleet');
        $data['activeDriversCount'] = $this->driverModel->getActiveDriversCount($fleet);
        $data['inactiveDriversCount'] = $this->driverModel->getInactiveDriversCount($fleet);
		$data['driversPerWeek'] = $this->obracunModel->getDriversPerWeek($fleet);
		$data['title'] = 'Drivers Dashboard';
        return view('drivers/index', $data);
    }

    // Display form to add a new driver
    public function active()
    {
        $session = session();
		$fleet = $session->get('fleet');
        $data['drivers'] = $this->driverModel->getActiveDrivers($fleet);
		$data['title'] = 'Vozači';
		
       return view('drivers/active', $data);
    }
	
	
    public function create()
    {
        $session = session();
		$fleet = $session->get('fleet');
		
		$data['title'] = 'Dodaj Vozača';
        return view('drivers/create', $data);
    }

    // Save new driver to the database
    public function store()
    {
        $this->driversModel->save([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'fleet_id' => $this->request->getPost('fleet_id'),
            'status' => $this->request->getPost('status'),
            'license_number' => $this->request->getPost('license_number')
        ]);

        return redirect()->to('/drivers/index/' . $this->request->getPost('fleet_id'));
    }

    // Show the edit form for a specific driver
    public function edit($id)
    {
        $data['driver'] = $this->driversModel->find($id);

        if (!$data['driver']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Driver not found');
        }

        return view('drivers/edit', $data);
    }

    // Update the driver's details
    public function update($id)
    {
        $this->driversModel->update($id, [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'status' => $this->request->getPost('status'),
            'license_number' => $this->request->getPost('license_number')
        ]);

        return redirect()->to('/drivers/index/' . $this->request->getPost('fleet_id'));
    }

    // Delete a driver from the database
    public function delete($id)
    {
        $this->driversModel->delete($id);
        return redirect()->back();
    }
}
