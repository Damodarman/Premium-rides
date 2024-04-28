<?php

namespace App\Models;

use CodeIgniter\Model;

class UltramsgLibConfigModel extends Model
{
    protected $table = 'UltramsgLibConfig';

    protected $allowedFields = [
		'id',
		'api_url', 
		'Instance_ID',
		'Token', 
		'vozac_id', 
		'fleet_id', 
	
	];
}