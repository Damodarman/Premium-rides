<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class ObracunFirmaModel extends Model{
    protected $table = 'obracunFirma';
    
    protected $allowedFields = [
        'uberNeto',
        'uberGotovina',
        'uberRazlika',
        'boltNeto',
        'boltRazlika',
        'boltGotovina',
        'myPosNeto',
        'myPosGotovina',
        'myPosRazlika',
        'ukupnoNetoSvi',
        'ukupnoGotovinaSvi',
        'ukupnoRazlikaSvi',
        'provizija',
        'week',
        'fleet',
        'fiskalizacijaBolt',
        'fiskalizacijaUber',
		'taximetar',
        'firmaFiskalizacija',
        'zaIsplatu',
        'refBonus',
        'trebaOstatFirmi',
        'zaradaFirme',
        'naplaceniTroskovi',
        'uberNapojnica',
        'uberPovrati',
        'boltNapojnica',
        'boltPovrati',
        'myPosNapojnica',
        'myPosPovrati',
        'doprinosi',
        'myPosPovrati',
        'netoPlaca',
        'myPosPovrati',
        'ukupnoNapojnicaSvi',
        'ukupnoPovratiSvi',
        'sveSlikeSpremljene',
    ];
}