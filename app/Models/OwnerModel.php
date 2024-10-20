<?php

namespace App\Models;

use CodeIgniter\Model;

class OwnerModel extends Model
{
    protected $table      = 'Owner';  // Correct table name
    protected $primaryKey = 'owner_id';

    protected $allowedFields = [
        'owner_type', 'owner_name', 'oib', 'contact_phone', 'contact_email', 'address', 'user_id', 'fleet', 'created_at', 'updated_at'
    ];

    protected $useTimestamps = true;  // Automatically manage created_at, updated_at
}
