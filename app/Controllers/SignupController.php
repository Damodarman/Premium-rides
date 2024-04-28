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
  
    public function store()
    {
        helper(['form']);
        $rules = [
            'name'          => 'required|min_length[2]|max_length[50]',
            'email'         => 'required|min_length[4]|max_length[100]|valid_email|is_unique[users.email]',
            'password'      => 'required|min_length[4]|max_length[50]',
            'confirmpassword'  => 'matches[password]'
        ];
          
        if($this->validate($rules)){
            $userModel = new UserModel();
            $data = [
                'name'     => $this->request->getVar('name'),
                'email'    => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ];
            $userModel->save($data);
            return redirect()->to('/index.php/signin');
        }else{
            $data['validation'] = $this->validator;
            echo view('signup', $data)
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