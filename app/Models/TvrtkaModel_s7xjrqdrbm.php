<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class TvrtkaModel extends Model{
    protected $table = 'tvrtka';
    
    protected $allowedFields = [
		'id',
        'naziv',
        'adresa',
        'postanskiBroj',
        'grad',
        'država',
        'OIB',
        'direktor',
    ];
}
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";