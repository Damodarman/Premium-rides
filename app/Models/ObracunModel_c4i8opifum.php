<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class ObracunModel extends Model{
    protected $table = 'obracun';
    
    protected $allowedFields = [
        'vozac',
        'boltNeto',
        'boltGotovina',
        'boltRazlika',
        'uberNeto',
        'uberGotovina',
        'uberRazlika',
        'myPosNeto',
        'myPosGotovina',
        'myPosRazlika',
        'password',
        'password',
        'password',
        'password',
        'password',
        'password',
        'created_at'
    ];
}
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";