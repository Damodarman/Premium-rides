<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class ObracunModel extends Model{
    protected $table = 'obracun';
    
    protected $allowedFields = [
        'vozac_id',
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
        'ukupnoNeto',
        'ukupnoGotovina',
        'ukupnoRazlika',
        'provizija',
        'taximetar',
        'fleet',
        'week',
		'referals',
		'refered_by',
		'referal_reward',
		'bonus_ref',
		'doprinosi',
		'zaIsplatu',
		'bruto_placa',
		'uberNapojnica',
		'uberPovrat',
		'boltNapojnica',
		'boltNaljepnice',
		'boltPovrat',
		'myPosNapojnica',
		'myPosPovrat',
		'ukupnoNapojnica',
		
		'uberOnline',
		'uberActiv',
		'uberPerH',
		'boltOnline',
		'boltActiv',
		'boltPerH',
		'totalPerH',
		'uberPerOH',
		'boltPerOH',
		'totalPerOH',
		
		'ukupnoPovrat',
		'cetvrtinaNetoPlace',
		'fiskalizacijaUber',
		'dug',
		'raspon',
		'isplataNa',
		'IBAN',
		'najamVozila',
		'fiskalizacijaBolt',
		'myPosTransakcije',
		'slikaObracuna'
    ];
}