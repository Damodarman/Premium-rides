<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 

class TaximetarReportModel extends Model
{
    protected $table = 'taximetar_reports';
    protected $allowedFields = [
        'Br', 
        'Ime_vozaca', 
        'Tel_broj',
        'Email_vozaca',
        'Ukupni_promet',
        'fleet',
        'week',
    ];
}