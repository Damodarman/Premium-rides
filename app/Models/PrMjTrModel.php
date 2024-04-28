<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class PrMjTrModel extends Model{
    protected $table = 'prMjTrgovca';
    
    protected $allowedFields = [
		'id',
        'trgovci_id',
        'mjestoBroj',
        'poDanu',
    ];
}