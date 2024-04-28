<?php

namespace App\Models;

use CodeIgniter\Model;

class PrijaveModel extends Model
{
    protected $table = 'prijave';

    protected $allowedFields = [
			'id',
			'vozac_id',
			'ime',
			'prezime',
			'OIB',
			'dob',
			'pocetak_prijave',
			'pocetak_promjene',
			'broj_sati',
			'IBAN',
			'zasticeniIBAN',
			'strani_IBAN',
			'radno_mjesto',
			'prekid_rada',
			'prvi_unos',
			'nadopuna',
			'timestamp',
			'user_id',
			'fleet',
	
		];
}
