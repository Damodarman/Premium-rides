<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrationInsuranceModel extends Model
{
    protected $table = 'RegistrationInsurance';
    protected $primaryKey = 'reg_ins_id';

    // Allowable fields for insertion and updates
    protected $allowedFields = [
        'vehicle_id',
        'registration_expiry',
        'insurance_expiry',
        'insurance_provider',
        'insurance_policy_number',
        'fleet',  // Add fleet
        'user_id',
        'created_at',
        'updated_at',
    ];

    // Automatically manage timestamps
    protected $useTimestamps = true;
}
