<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class TaximetarLogsModel extends Model{
    protected $table = 'taximetar_logs';
    
    protected $allowedFields = [
		'id',
        'vozac_id',
        'vozac',
        'brojMoba',
        'OIB',
        'modelMoba',
        'email',
        'regVozila',
        'administrator_id',
        'administrator',
        'timestamp',
        'ukljucenje',
        'iskljucenje',
        'promjena',
        'fleet',
    ];
}