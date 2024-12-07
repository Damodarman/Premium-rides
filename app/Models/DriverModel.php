<?php

namespace App\Models;

use CodeIgniter\Model;

class DriverModel extends Model
{
    protected $table = 'vozaci';

    protected $allowedFields = [
		'vozac',
		'ime', 
		'prezime',
		'email', 
		'mobitel', 
		'adresa', 
		'grad', 
		'drzava', 
		'postanskiBroj', 
		'dob', 
		'oib', 
		'uber', 
		'bolt', 
		'taximetar',
		'mobTaximetar',
		'myPos',
		'refered_by', 
		'referal_reward', 
		'vrsta_provizije', 
		'iznos_provizije', 
		'popust_na_proviziju', 
		'popust_na_taximetar', 
		'uber_unique_id', 
		'bolt_unique_id', 
		'myPos_unique_id',
		'taximetar_unique_id',
		'prijava',
		'broj_sati',
		'blagMin',
		'blagMax',
		'fleet',
		'aktivan',
		'sezona',
		'radno_mjesto',
		'provizijaNaljepnice',
		'vrsta_nagrade',
		'nacin_rada',
		'tjedna_isplata',
		'isplata_place',
		'IBAN',
		'zasticeniIBAN',
		'strani_IBAN',
		'vrsta_zaposlenja',
		'kraj_prijave',
		'pocetak_prijave',
		'whatsApp',
		'radniOdnos',
		'pocetak_promjene',
		'pocetak_rada'
	
	];
	
	
	public function getActiveDriversCount($fleet){
	return $this->where('fleet', $fleet)->where('aktivan', 1)->countAllResults();	
	}

	public function getInactiveDriversCount($fleet){
		return $this->where('fleet', $fleet)->where('aktivan', 0)->countAllResults();	
	}

	public function getAllDriversCount($fleet){
		return $this->where('fleet', $fleet)->countAllResults();	
	}
	
	public function getActiveDrivers($fleet){
		return $this->where('fleet', $fleet)->where('aktivan', 1)->get()->getResultArray();
	}
	public function getInactiveDrivers($fleet){
		return $this->where('fleet', $fleet)->where('aktivan', 0)->get()->getResultArray();
	}
	public function getNameById($id){
		$driver = $this->where('id', $id)->first();
		return $driver['vozac'];
	}
	public function getDriverById($id){
		return $this->where('id', $id)->first();
	}
	
}