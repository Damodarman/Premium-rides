<?php

namespace App\Models;

use CodeIgniter\Model;

class VehicleEquipmentModel extends Model
{
    protected $table      = 'VehicleEquipment';  // Table name
    protected $primaryKey = 'equipment_id';  // Primary key
    
    protected $allowedFields = [
        'vehicle_id', 'fire_extinguisher_validity', 'first_aid_kit_status', 
        'yellow_paper_validity', 'user_id', 'created_at', 'updated_at', 'fleet'
    ];
    
    protected $useTimestamps = true;  // Automatically handle `created_at` and `updated_at`
}
