<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
  
class ProfileController extends Controller
{
    public function index()
    {
        $session = session();
		$data = $session->get();
		$role = $session->get('role');
		if ($role == 'admin'){
			return redirect()-> to('admin');
		}
		elseif($role == 'voditelj'){
			return redirect()-> to('voditelj');
			
		}
		elseif($role == 'knjigovoda'){
			return redirect()-> to('knjigovoda');
			
		}
			else{
			return view('profile', $data)
				. view('footer');
		}
        //echo "Hello : ".$session->get('name');
    }
	
	public function logout()
	{
        $session = session();
		$session->destroy();
        return redirect()->to('home');
		
	}
}