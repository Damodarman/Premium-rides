<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class FiskalizacijaModel extends Model{
    protected $table = 'fiskalizacija';
    
    protected $allowedFields = [
        'vozac_id',
        'vozac',
        'fleet',
        'month',
        'bolt_fiskalizacija',
        'uber_fiskalizacija',
    ];
}