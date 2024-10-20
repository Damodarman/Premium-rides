<?php

namespace App\Models;

use CodeIgniter\Model;

class MonthlyVehicleReportModel extends Model
{
    protected $table = 'MonthlyVehicleReport';
    protected $primaryKey = 'report_id';  // Updated to match the primary key name

    protected $allowedFields = [
        'vehicle_id',
        'check_type',  // Added
        'check_date',  // Added
        'brakes_condition',  // Updated to match the DB
        'shock_absorbers',  // Updated to match the DB
        'oil_level',  // Updated to match the DB
        'coolant_level',  // Updated to match the DB
        'adblue_level',  // Updated to match the DB
        'engine_mounts',  // Updated to match the DB
        'fleet',
        'user_id',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
}
