<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 
class UberReportModel extends Model
{
    protected $table = 'uber_reports';
    protected $allowedFields = [
        'UUID_vozaca', 
        'Vozac', 
        'Vozacevo_ime', 
        'Vozacevo_prezime',
        'Ukupna_zarada',
        'Ukupna_zarada_Neto_cijena',
        'Povrati_i_troskovi',
        'Isplate',
        'Isplate_Preneseno_na_bankovni_racun',
        'Isplate_Naplaceni_iznos_u_gotovini',
        'Povrati_i_troskovi_Povrati_Pristojba_za_zracnu_luku',
        'Povrati_i_troskovi_Povrati_Cestarina',
        'Ukupna_zarada_Napojnica',
        'Ukupna_zarada_Promocije',
        'fleet',
		'report_for_week',
		'Ukupna_zarada_Ostala_zarada_Povrat_izgubljenih_predmeta'
    ];
}

