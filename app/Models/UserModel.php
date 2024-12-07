<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class UserModel extends Model{
    protected $table = 'users';
    
    protected $allowedFields = [
        'name',
        'email',
        'password',
        'created_at',
		'level',
		'vozac_id',
		'fleet',
		'comfirmed',
		'verification_token',
		'password_reset_token',
		'phone',
		'role'
    ];
	
	public function getAllFleetUsers($fleet){
		return $this->where('fleet', $fleet)->findAll();
	}
	
	public function getUserById($userId){
		return $this->where('id', $userId)->first();
	}
	public function getUserNameById($userId){
		$user = $this->where('id', $userId)->first();
		return $user['name'];
	}
	public function getUserPhoneById($userId){
		$user = $this->where('id', $userId)->first();
		return $user['phone'];
	}
}