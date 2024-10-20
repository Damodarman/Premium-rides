<?php

namespace App\Models;

use CodeIgniter\Model;

class VehicleAssignmentModel extends Model
{
    protected $table      = 'VehicleAssignment';  // Table name
    protected $primaryKey = 'assignment_id';  // Primary key
    
    protected $allowedFields = [
        'vehicle_id', 'vozac_id', 'pickup_date', 'dropoff_date', 
        'damages_on_pickup', 'damages_on_dropoff', 'vehicle_condition_on_pickup', 
        'vehicle_condition_on_dropoff', 'fuel_on_pickup', 'fuel_on_dropoff', 
        'rent_price', 'user_id', 'created_at', 'updated_at'
    ];
    
    protected $useTtimestamps = true;  // Automatically handle `created_at` and `updated_at`
}
