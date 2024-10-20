<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\FlotaModel;
  
class SigninController extends Controller
{
    public function index()
    {
        helper(['form']);
        echo view('signin')
			. view('footer');
    } 
  
    public function loginAuth()
    {
		$session = session();
		$userModel = new UserModel();
		$FlotaModel = new FlotaModel();

		// Validation rules for email and password
		$validationRules = [
			'email' => 'required|valid_email',
			'password' => 'required|min_length[6]' // Example: Minimum password length of 6 characters
		];

		// Run the validation
		if (!$this->validate($validationRules)) {
			// If validation fails, redirect back to the login page with validation errors
			$session->setFlashdata('msg', 'Please enter a valid email and password.');
			session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to('signin')->withInput();
		}
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        
        $data = $userModel->where('email', $email)->first();
		
		$fleet = $data['fleet'];
		if(empty($fleet)){
			$fleet = 'Premium Rides';
		}
		$fleetID = $FlotaModel->select('id')->where('naziv', $fleet)->get()->getRowArray();
		$fleetID = $fleetID['id'];
        
        if($data){
            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if($authenticatePassword){
				if($data['comfirmed'] != 0){
					$ses_data = [
						'id' => $data['id'],
						'name' => $data['name'],
						'email' => $data['email'],
						'phone' => $data['phone'],
						'role' => $data['role'],
						'fleet' => $data['fleet'],
						'level' => $data['level'],
						'fleet_id' => $fleetID,
						'isLoggedIn' => TRUE
					];
					$session->set($ses_data);
					return redirect()->to('profile');
				}else{
					$session->setFlashdata('msg', 'Your account is not verifyed, please check your ' .$data['email'].' to verify your account' );
					session()->setFlashdata('alert-class', 'alert-warning');
					return redirect()->to('signin');
				}
            
            }else{
                $session->setFlashdata('msg', 'Password is incorrect.');
                session()->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to('signin');
            }
        }else{
            $session->setFlashdata('msg', 'Email does not exist.');
			session()->setFlashdata('alert-class', 'alert-danger');
            return redirect()->to('signin');
        }
    }
}
