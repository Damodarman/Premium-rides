<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\DriverModel;
use App\Models\UserTokensModel;


use App\Libraries\UltramsgLib;

class SignupController extends Controller
{
    public function index()
    {
        helper(['form']);
        $data = [];
        echo view('signup', $data)
			. view('footer');
    }
  
	public function generateToken($length = 16) {
		return bin2hex(random_bytes($length));
}
	
	public function newPasswordSave(){
		$session = session();
		$userModel = new UserModel();

        helper(['form']);
        $rules = [
            'password'      => 'required|min_length[4]|max_length[50]',
            'confirmpassword'  => 'matches[password]'
        ];
        if($this->validate($rules)){
			$email = $this->request->getVar('email');
			$password_reset_token = $this->request->getVar('token');
			$user = $userModel->where('email', $email)->where('password_reset_token', $password_reset_token)->get()->getRowArray();
			if(empty($user)){
				$session->setFlashdata('msgPoruka', ' The request is not valid and you have been redirected to sign in.');
				session()->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to('/index.php/signin');
			}else{
				$id = $user['id'];
				$data = [
					'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
					'password_reset_token' => null
				];
				if($userModel->update($id, $data)){
					$session->setFlashdata('msgPoruka', ' The password has been changed successfuly and you can sign in now.');
					session()->setFlashdata('alert-class', 'alert-success');
					return redirect()->to('/index.php/signin');
				}else{
					$session->setFlashdata('msgPoruka', ' The password has not been changed. Please try later');
					session()->setFlashdata('alert-class', 'alert-danger');
					return redirect()->to('/index.php/signin');
				}
			}
		}else{
			$session->setFlashdata('msgPoruka', ' The passwords do not match. Please try again');
			session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to('/index.php/signin');
		}
		
		
	}
	
	
    public function store()
    {
		 		$session = session();

        helper(['form']);
        $rules = [
            'name'          => 'required|min_length[2]|max_length[50]',
            'email'         => 'required|min_length[4]|max_length[100]|valid_email|is_unique[users.email]',
            'password'      => 'required|min_length[4]|max_length[50]',
            'confirmpassword'  => 'matches[password]'
        ];
          
        if($this->validate($rules)){
			$token = $this->generateToken();
            $userModel = new UserModel();
            $data = [
                'name'     => $this->request->getVar('name'),
                'email'    => $this->request->getVar('email'),
				'verification_token' => $token,
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];
            if($userModel->save($data)){
				$email = $this->request->getVar('email');
				$name = $this->request->getVar('name');
				
				$this->sendVerificationEmail($email, $token);
				
				$session->setFlashdata('msgPoruka', ' Dear ' .$name .', registration was successful. Please check your ' .$email .' inbox for verification email');
				session()->setFlashdata('alert-class', 'alert-success');
				return redirect()->to('/index.php/signin');
			}else{
				$session->setFlashdata('msgPoruka', ' Registration failed, please try again.');
				session()->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to('/index.php/signup');
			}
			
        }else{
            $data['validation'] = $this->validator;
            echo view('signup', $data)
				. view('footer');
        }
          
    }
	
	function sendVerificationEmail($email, $token) {
		$emailService = \Config\Services::email();

		$verificationLink = base_url("index.php/emailVerification/$token");

		$emailService->setTo($email);
		$emailService->setSubject('Please Verify Your Email');
		$emailService->setMessage("Click the following link to verify your email: <a href=\"$verificationLink\">Verify</a>");

		return $emailService->send();
	}	
	
	function sendPasswordResetLink($email, $token) {
		$emailService = \Config\Services::email();

		$verificationLink = base_url("index.php/resetPassword/$token/$email");

		$emailService->setTo($email);
		$emailService->setSubject('Reset your Password');
		$emailService->setMessage("Click the following link to reset your Password: <a href=\"$verificationLink\">$verificationLink</a>");

		return $emailService->send();
	}	
	
	public function resetPassword($token, $email){
		$userModel = new UserModel();
	 	$session = session();
		$user = $userModel->where('email', $email)->where('password_reset_token', $token)->get()->getRowArray();
		if(empty($user)){
			$session->setFlashdata('msgPoruka', ' The token is not valid and you have been redirected to sign in.');
			session()->setFlashdata('alert-class', 'alert-danger');
			return redirect()->to('/index.php/signin');
		}else{
			$data=[
				'id' => $user['id'],
				'email' => $email,
				'token' => $token				
			];
			
		 echo view('newPassword', $data)
				. view('footer');

		}
	}
	
	
	public function confirmToken($token){
		$userModel = new UserModel();
	 	$session = session();
	
		$user = $userModel->where('verification_token', $token)->get()->getRowArray();
		if(empty($user)){
				$session->setFlashdata('msgPoruka', ' The token is incorrect or the email has already been verified. Please try signing in again.');
				session()->setFlashdata('alert-class', 'alert-warning');
				return redirect()->to('/index.php/signin');
		}else{
			$createdAt = $user['created_at'];
			$expired = $this->tokenAge($createdAt);
			var_dump($expired);
			if($expired){
				$session->setFlashdata('msgPoruka', ' The token is expired.');
				session()->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to('/index.php/signup');
			}else{
				$id = $user['id'];
				$data =[
					'comfirmed' => 1,
					'verification_token' => null
				];
				if($userModel->update($id, $data)){
					$session->setFlashdata('msgPoruka', ' The email is verifyed. You can sign in now ');
					session()->setFlashdata('alert-class', 'alert-success');
					return redirect()->to('/index.php/signin');
				}else{
					$session->setFlashdata('msgPoruka', ' Unknown error occured.');
					session()->setFlashdata('alert-class', 'alert-danger');
					return redirect()->to('/index.php/signup');
				}
			}
			
		}
		
	}
	
	public function tokenAge($createdAt){
		$storedTimestamp = $createdAt;

		// Current time
		$now = new \DateTime('now');

		// Create a 12-hour interval
		$interval = new \DateInterval('PT12H'); // PT12H means 12 hours
		$threshold = (clone $now)->sub($interval); // Subtract 12 hours from current time

		// Convert the stored timestamp to a DateTime object
		$storedTime = new \DateTime($storedTimestamp);

		// Check if stored time is older than the 12-hour threshold
		if ($storedTime < $threshold) {
			return true;
		} else {
			return false;
		}
	}
	
	public function passwordRecovery(){
		        helper(['form']);
        $data = [];
        echo view('passwordrecovery', $data)
			. view('footer');

	}
	
	public function passwordReset(){
		helper(['form']);
		$userModel = new UserModel();
		$session = session();
        $rules = [
            'email'         => 'required|min_length[4]|max_length[100]|valid_email',
        ];
		
        if($this->validate($rules)){
			$email = $this->request->getVar('email');
			$user = $userModel->where('email', $email)->get()->getRowArray();
			if(empty($user)){
				$session->setFlashdata('msgPoruka', ' The email is incorect, try again ');
				session()->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to('/index.php/passwordRecovery');
			}else{
				$userId = $user['id'];
				$token = $this->generateToken();
				$data['password_reset_token'] = $token;
				if($userModel->update($userId, $data)){
						$email = $user['email'];
						$name = $user['name'];

						$this->sendPasswordResetLink($email, $token);
						$session->setFlashdata('msgPoruka', 'Please check your ' .$email .' inbox for password reset email');
						session()->setFlashdata('alert-class', 'alert-success');
						return redirect()->to('/index.php/signin');
					
				}else{
					$session->setFlashdata('msgPoruka', ' Something gone wrong, please try again ');
					session()->setFlashdata('alert-class', 'alert-danger');
					return redirect()->to('/index.php/passwordRecovery');
				}

			}
		}else{
            $data['validation'] = $this->validator;
            echo view('passwodRecovery', $data)
				. view('footer');
		}
		
	}
	
	
	public function comfirmNumber(){
		
		
	}
  
    public function storeMob()
    {
 		$session = session();
       helper(['form']);
        $rules = [
            'name'          => 'required|min_length[2]|max_length[50]',
            'phone_number'         => 'required|min_length[4]|max_length[100]|is_unique[users.phone]',
            'password'      => 'required|min_length[4]|max_length[50]',
            'confirmpassword'  => 'matches[password]'
        ];
          
        if($this->validate($rules)){
			$UltramsgLib = new UltramsgLib();

            $userModel = new UserModel();
			$phone = $this->request->getVar('phone_number');
			$checkNumber = $UltramsgLib->checkContact($phone);
			
			if($checkNumber['status'] != 'valid'){
				$session->setFlashdata('msgPoruka', "$phone nije valjani WhatsApp broj.");
				session()->setFlashdata('alert-class', 'alert-danger');
				return redirect()->to('/index.php/signup');
			}
			$DriverModel = new DriverModel();
			$vozac = $DriverModel->where('mobitel', $phone)->get()->getRowArray();
			$vozac_id = 0;
			$email = 'nemamail';
			$role = 'user';
			$fleet = NULL;
			if($vozac){
				$vozac_id = $vozac['id'];
				$email = $vozac['email'];
				$role = 'vozac';
				$fleet = $vozac['fleet'];
			}
            $data = [
                'name'     => $this->request->getVar('name'),
                'phone'    => $this->request->getVar('phone_number'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
				'vozac_id' => $vozac_id,
				'email' => $email,
				'fleet' => $fleet,
				'role' => $role
            ];
            $userModel->save($data);
			$user_id = $userModel->insertID();
			
			$userTokensModel = new UserTokensModel();
			
			$seed = intval((double)microtime() * 1000000);
			mt_srand($seed);
			$token = mt_rand(1000, 9999);

			$tempData = [
				'user_id' => $user_id,
				'token' => $token
			];
			$userTokensModel->save($tempData);
			$tokenID = $userTokensModel->insertID();
			$time = $userTokensModel->where('id', $tokenID)->get()->getRowArray();
			$data['user_id'] = $user_id;
			$data['time'] = $time['time'];
			$data['page'] = 'Potvrdi broj mobitela';
			
			$msg ="Za potvrdu svog broja na aplikaciji Premium Rides koristi ovaj četveroznamenkasti kod $token ";
			
			$poruka['to'] = $phone;
			$poruka['msg'] = $msg;
			$poruka = $UltramsgLib->sendMsg($poruka);

			 echo view('adminDashboard/header', $data)
				 . view('userComfirmation')
				. view('footer');

        }else{
            $data['validation'] = $this->validator;
            echo view('signup', $data)
				. view('footer');
        }
          
    }
	
}