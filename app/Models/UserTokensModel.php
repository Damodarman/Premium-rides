<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class UserTokensModel extends Model{
    protected $table = 'userTokens';
    
    protected $allowedFields = [
        'id',
        'user_id',
        'token',
        'time',
    ];
}