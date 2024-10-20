<?php

namespace App\Models;

use CodeIgniter\Model;

class WeeklyVehicleCheckModel extends Model
{
    protected $table = 'WeeklyVehicleCheck';
    protected $primaryKey = 'check_id';  // Updated to match the primary key name

    protected $allowedFields = [
        'vehicle_id',
        'check_type',  // Added
        'check_date',  // Added
        'body_damage',  // Updated to match the DB
        'lights_condition',  // Updated to match the DB
        'wipers_status',  // Updated to match the DB
        'tyres_status',
		'dashboard_warning_lights',// Updated to match the DB
        'fleet',
        'user_id',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}
