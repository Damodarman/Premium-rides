<?php

namespace App\Models;

use CodeIgniter\Model;

class MalfunctionModel extends Model
{
    protected $table      = 'Malfunction';  // Table name
    protected $primaryKey = 'malfunction_id';  // Primary key
    
    protected $allowedFields = [
        'vehicle_id', 'vozac_id', 'user_id', 'malfunction_date', 'details', 
        'status', 'vehicle_status', 'fix_date', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;  // Automatically handle `created_at` and `updated_at`
}
