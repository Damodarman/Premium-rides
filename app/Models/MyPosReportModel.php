<?php 
namespace App\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
 

class MyPosReportModel extends Model
{
    protected $table = 'myPos_reports';
    protected $allowedFields = [
        'Terminal_name', 
        'Date_initiated', 
        'Date_settled',
        'Type',
        'Transaction_reference',
        'Reference_number',
        'Description',
        'Payment_from_card',
        'Amount',
		'Currency',
		'fleet',
		'report_for_week'
    ];
	
	public function getDriverReports($startWeek, $endWeek, $myPosUniqueId, $fleet){
		
		
		return $this->select('Date_initiated, Type, Amount, report_for_week')
					->where('fleet', $fleet)
					->where('Terminal_name', $myPosUniqueId)
					->where('report_for_week >= ', $startWeek)
					->where('report_for_week <= ', $endWeek)
					->findAll();
			
		
	}
	
	
}