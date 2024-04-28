<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class ObracunFirmaModel extends Model{
    protected $table = 'obracunFirma';
    
    protected $allowedFields = [
        'uberNetoSvi',
        'uberGotovinaSvi',
        'uberRazlikaSvi',
        'boltNetoSvi',
        'boltRazlikaSvi',
        'boltGotovinaSvi',
        'myPosNetoSvi',
        'myPosGotovinaSvi',
        'myPosRazlikaSvi',
        'firmaNeto',
        'firmaGotovina',
        'firmaRazlika',
        'firmaProvizija',
        'week',
        'fleet',
    ];
}
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";