<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class FlotaModel extends Model{
    protected $table = 'postavke_flote';
    
    protected $allowedFields = [
		'id',
        'naziv',
        'vlasnik',
        'vlasnik_users_id',
        'fiskalizacija_bolt',
        'fiskalizacija_uber',
        'provizija_fiks',
        'provizija_postotak',
        'taximetar',
        'taximetar_provizija',
        'tvrtka_naziv',
        'tvrtka_id',
        'imaWhatsApp',
        'koristi_min_proviziju',
        'iznos_min_provizije',
        'created_at'
    ];
}