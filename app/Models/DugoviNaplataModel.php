<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class DugoviNaplataModel extends Model{
    protected $table = 'dugovi_naplata';
    
    protected $allowedFields = [
		'id',
        'user_id',
        'vozac',
        'dug_id',
        'user',
        'timestamp',
        'nacin_placanja',
		'iznos',
		'predano',
		'primljeno',
		'fleet'
    ];
}