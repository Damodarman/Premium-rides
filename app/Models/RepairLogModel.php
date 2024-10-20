<?php

namespace App\Models;

use CodeIgniter\Model;

class RepairLogModel extends Model
{
    protected $table      = 'RepairLog';  // Table name
    protected $primaryKey = 'repair_id';  // Primary key
    
    protected $allowedFields = [
        'malfunction_id', 'repair_details', 'repaired_by', 'repair_date', 
        'vehicle_status_after_repair', 'user_id', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;  // Automatically handle `created_at` and `updated_at`
}
