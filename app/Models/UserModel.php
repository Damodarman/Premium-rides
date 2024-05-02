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
}