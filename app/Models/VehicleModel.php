<?php

namespace App\Models;

use CodeIgniter\Model;

class VehicleModel extends Model
{
    protected $table      = 'Vehicle';  // Table name
    protected $primaryKey = 'vehicle_id';  // Primary key
    
protected $allowedFields = [
    'make', 'model', 'license_plate', 'vin', 'kilometers', 'year', 'eko_norm', 
    'user_id', 'completion_step', 'completion_status', 'created_at', 'updated_at', 'fleet'
];
	
	
    protected $useTimestamps = true;  // Automatically handle `created_at` and `updated_at`
	
	
public function getActiveVehiclesCount($fleet){
	return $this->where('fleet', $fleet)->where('status', 'in_service')->countAllResults();	
}
	
public function getInactiveVehiclesCount($fleet){
	return $this->where('fleet', $fleet)->where('status', 'out_of_order')->countAllResults();	
}
	
public function getAllVehiclesCount($fleet){
	return $this->where('fleet', $fleet)->countAllResults();	
}
	
	
	
}
