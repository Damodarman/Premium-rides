<?php

namespace App\Models;

use CodeIgniter\Model;

class OwnershipModel extends Model
{
    protected $table      = 'Ownership';  // Correct table name
    protected $primaryKey = 'ownership_id';

    protected $allowedFields = [
        'vehicle_id', 'vozac_id', 'owner_id', 'ownership_type', 'company_id', 'rental_details', 'weekly_price', 'user_id', 'fleet', 'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;  // Automatically manage created_at, updated_at
}
