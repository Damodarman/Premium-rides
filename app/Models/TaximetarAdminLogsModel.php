<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class TaximetarAdminLogsModel extends Model{
    protected $table = 'taximetar_admin_logs';
    
    protected $allowedFields = [
		'id',
        'vozac_id',
        'user_id',
        'timestamp',
        'ukljucenje',
        'iskljucenje',
        'promjena',
        'fleet',
    ];
}