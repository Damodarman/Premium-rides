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
		'radno_mjesto',
		'provizijaNaljepnice',
		'vrsta_nagrade',
		'nacin_rada',
		'isplata',
		'IBAN',
		'zasticeniIBAN',
		'strani_IBAN',
		'pocetak_prijave',
		'whatsApp',
		'radniOdnos',
		'pocetak_promjene',
		'pocetak_rada'
	
	];
}