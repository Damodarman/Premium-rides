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
	
	public function getDriverReports($startWeek, $endWeek, $taximetarUniqueId, $fleet){


	return $this->select('Ukupni_promet, week')
				->where('fleet', $fleet)
				->where('Email_vozaca', $taximetarUniqueId)
				->where('week >= ', $startWeek)
				->where('week <= ', $endWeek)
				->findAll();
			
		
	}

}