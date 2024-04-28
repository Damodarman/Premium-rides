<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 

class BoltReportModel extends Model
{
    protected $table = 'bolt_reports';
    protected $allowedFields = [
        'Vozac', 
        'Telefonski_broj_vozaca', 
        'Vozacevo_prezime',
        'Period',
        'Bruto_iznos',
        'Otkazna_naknada',
        'Naknada_za_rezervaciju_placanje',
        'Naknada_za_rezervaciju_odbitak',
        'Naknada_za_cestarinu',
		'Bolt_naknada',
        'Voznje_placene_gotovinom_prikupljena_gotovina',
        'Popusti_na_vožnje_na_gotovinu_od_Bolt',
        'Bonus',
        'Nadoknade',
        'Povrati_novca',
        'Napojnica',
        'Ima_radno_vrijeme_za_narudzbe',
        'Tjedno_stanje_racuna',
        'Sati_na_mrezi',
        'Utilization',
        'fleet',
		'report_for_week'
    ];
}
echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";