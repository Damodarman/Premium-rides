<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class UberReportModel extends Model
{
    protected $table = 'uber_reports';
    protected $allowedFields = [
        'UUID_vozaca', 
        'Vozacevo_ime', 
        'Vozacevo_prezime',
        'Ukupna_zarada',
        'Ukupna_zarada_Neto_cijena',
        'Povrati_i_troskovi',
        'Isplate',
        'Isplate_Preneseno_na_bankovni_racun',
        'Isplate_Naplaceni_iznos_u_gotovini',
        'Povrati_i_troskovi_Povrati_Pristojba_za_zracnu_luku',
        'Ukupna_zarada_Napojnica',
        'Ukupna_zarada',
        'Ukupna_zarada'
    ];
}
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";