<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
  
class KnjigovodaController extends Controller
{
    public function index()
    {
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