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
        'taximetar_provizija',
        'created_at'
    ];
}
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";