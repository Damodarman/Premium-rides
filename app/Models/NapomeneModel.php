<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class NapomeneModel extends Model{
    protected $table = 'napomene';
    
    protected $allowedFields = [
		'id',
        'timestamp',
        'user',
        'driver_id',
        'napomena',
        'vrsta_napomene',
        'fleet',
    ];
}