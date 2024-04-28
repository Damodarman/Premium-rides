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
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        
        $data = $userModel->where('email', $email)->first();
		
		$fleet = $data['fleet'];
		$fleetID = $FlotaModel->select('id')->where('naziv', $fleet)->get()->getRowArray();
		$fleetID = $fleetID['id'];
        
        if($data){
            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if($authenticatePassword){
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
                return redirect()->to('/index.php/profile');
            
            }else{
                $session->setFlashdata('msg', 'Password is incorrect.');
                return redirect()->to('/index.php/signin');
            }
        }else{
            $session->setFlashdata('msg', 'Email does not exist.');
            return redirect()->to('/index.php/signin');
        }
    }
}
