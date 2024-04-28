<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class DugoviModel extends Model{
    protected $table = 'dugovi';
    
    protected $allowedFields = [
		'id',
        'vozac_id',
        'vozac',
        'fleet',
        'week',
        'iznos',
        'placeno',
    ];
}