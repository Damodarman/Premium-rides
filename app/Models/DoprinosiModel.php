<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class DoprinosiModel extends Model{
    protected $table = 'trosak_doprinosi_placa';
    
    protected $allowedFields = [
		'broj_sati',
        'dop_do_30',
        'dop_od_30',
        'bruto_do_30',
        'bruto_od_30',
    ];
}