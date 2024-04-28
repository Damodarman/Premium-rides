<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class ReferalBonusModel extends Model{
    protected $table = 'referal_bonus';
    
    protected $allowedFields = [
        'vozac_id',
        'vozac',
        'refered_by',
        'referal_reward',
        'week',
    ];
}