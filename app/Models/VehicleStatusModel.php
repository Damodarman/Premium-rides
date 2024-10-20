<?php

namespace App\Models;

use CodeIgniter\Model;

class VehicleStatusModel extends Model
{
    protected $table = 'VehicleStatus';
    protected $primaryKey = 'status_id';

    // Allowable fields for insertion and updates
    protected $allowedFields = [
        'vehicle_id',
        'registration_expiry',
        'insurance_expiry',
        'dashboard_warning_lights',  // Added
        'service_interval',
        'fleet',
        'user_id',
        'created_at',
        'updated_at',
    ];

    // Automatically manage timestamps
    protected $useTimestamps = true;
}
