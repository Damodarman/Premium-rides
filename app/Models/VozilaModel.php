<?php

namespace App\Models;

use CodeIgniter\Model;

class VozilaModel extends Model
{
    protected $table = 'vozila';

    protected $allowedFields = [
		'proizvodac',
		'model', 
		'reg',
		'godina', 
		'vozac_id', 
		'changed', 
		'change_date', 
		'cijena_tjedno', 
		'cijena_po_km', 
		'placa_firma', 
		'fleet'
	
	];
}