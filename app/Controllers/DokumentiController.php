<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\PrijaveModel;
use App\Models\DriverModel;
  
class DokumentiController extends Controller
{
    public function index()
    {
		$prijaveModel = new PrijaveModel();
        $session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		
		
		$data['page'] = 'Dashboard knjigovođe';
		$data['fleet'] = $fleet;
		$data['role'] = $role;
		
		echo view('adminDashboard/header', $data)
			.view('adminDashboard/navBar')
			.view('adminDashboard/knjigovoda');
       
    }
		
}