<?php
namespace App\Models;

use CodeIgniter\Model;

class ActivityUberModel extends Model
{
    protected $table = 'activityUber';
    protected $primaryKey = 'id';
    protected $allowedFields = ['uuid_vozaca', 'vozac', 'ime', 'prezime', 'fleet', 'dovrsene_voznje', 'vrijeme_na_mrezi', 'vrijeme_voznje', 'datum_unosa'];
}
