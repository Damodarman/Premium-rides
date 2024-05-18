<?php
namespace App\Models;

use CodeIgniter\Model;

class BoltDriverActivityModel extends Model
{
    protected $table = 'bolt_driver_activity';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'driver_id', 'driver_name', 'phone_number', 'email', 
        'cash_trips_enabled', 'driver_success_rate', 'completed_rides', 
        'total_acceptance', 'required_acceptance', 'online_hours', 
        'active_driving_hours', 'utilization', 'rides_taken_rate', 
        'rides_completed_rate', 'average_rating', 'average_distance', 
        'fleet', 'start_date', 'end_date', 'personal_code'
    ];
}
