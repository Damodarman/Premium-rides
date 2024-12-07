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
	
	public function getDriversPerWeek($fleet)
	{
    return $this->select('obracun.week, COUNT(DISTINCT obracun.vozac_id) as driver_count, obracunFirma.zaradaFirme')
                ->join('obracunFirma', 'obracun.week = obracunFirma.week AND obracun.fleet = obracunFirma.fleet') // Join on week and fleet
                ->where('obracun.fleet', $fleet) // Apply fleet condition
                ->where('obracun.zaIsplatu !=', 0) 
                ->groupBy('obracun.week, obracunFirma.zaradaFirme') // Group by week and zaradaFirmi
                ->orderBy('obracun.week', 'ASC')
                ->findAll();
	}
	
	public function getObracunById($id){
		return $this->where('id', $id)->first();
	}
	
}