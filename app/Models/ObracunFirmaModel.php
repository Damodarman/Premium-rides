<?php 
namespace App\Models;  
use CodeIgniter\Model;
  
class ObracunFirmaModel extends Model{
    protected $table = 'obracunFirma';
    
    protected $allowedFields = [
        'uberNeto',
        'uberGotovina',
        'uberRazlika',
        'boltNeto',
        'boltRazlika',
        'boltGotovina',
        'myPosNeto',
        'myPosGotovina',
        'myPosRazlika',
        'ukupnoNetoSvi',
        'ukupnoGotovinaSvi',
        'ukupnoRazlikaSvi',
        'provizija',
        'week',
        'fleet',
        'fiskalizacijaBolt',
        'fiskalizacijaUber',
		'taximetar',
        'firmaFiskalizacija',
        'zaIsplatu',
        'refBonus',
        'trebaOstatFirmi',
        'zaradaFirme',
        'naplaceniTroskovi',
        'uberNapojnica',
        'uberPovrati',
        'boltNapojnica',
        'boltPovrati',
        'myPosNapojnica',
        'myPosPovrati',
        'doprinosi',
        'activity',
        'myPosPovrati',
        'netoPlaca',
        'myPosPovrati',
        'ukupnoNapojnicaSvi',
        'ukupnoPovratiSvi',
        'sveSlikeSpremljene',
    ];
	
public function platformRatio($fleet, $filters = []) {
    $builder = $this->where('fleet', $fleet);

    // Apply week filters if provided
    if (!empty($filters['startWeek']) && !empty($filters['endWeek'])) {
        $builder->where('week >=', $filters['startWeek'])
                ->where('week <=', $filters['endWeek']);
    }

    // Select platform data and sum it
    $builder->select('
        SUM(uberNeto) AS uber,
        SUM(boltNeto) AS bolt,
        SUM(myPosNeto) AS myPos,
        week
    ');
    $builder->groupBy('week');
    
    // Get the result
    $result = $builder->get()->getResultArray();
    
    // Calculate totals and percentages
    $totalUber = 0;
    $totalBolt = 0;
    $totalMyPos = 0;

    foreach ($result as $row) {
        $totalUber += $row['uber'];
        $totalBolt += $row['bolt'];
        $totalMyPos += $row['myPos'];
    }

    $grandTotal = $totalUber + $totalBolt + $totalMyPos;

    // Calculate percentages
    $percentUber = $grandTotal > 0 ? ($totalUber / $grandTotal) * 100 : 0;
    $percentBolt = $grandTotal > 0 ? ($totalBolt / $grandTotal) * 100 : 0;
    $percentMyPos = $grandTotal > 0 ? ($totalMyPos / $grandTotal) * 100 : 0;
	
	$weeks = array_column($result, 'week');
	
	$quarters = $this->getAvailableQuarters($weeks);

    // Return the result with totals and percentages
    return [
        'totals' => [
            'uber' => round($totalUber, 2),
            'bolt' => round($totalBolt, 2),
            'taximetar' => round($totalMyPos, 2),
            'grandTotal' => round($grandTotal, 2),
        ],
        'percentages' => [
            'uber' => round($percentUber, 2),
            'bolt' => round($percentBolt, 2),
            'taximetar' => round($percentMyPos, 2),
        ],
        'weeks' => $weeks,
		'quarters' => $quarters
    ];
}
	
function getAvailableQuarters($weeks) {
    $quarters = [];
    
    // Loop through weeks and map them to quarters
    foreach ($weeks as $week) {
        $year = substr($week, 0, 4);  // Extract year from week
        $weekNum = (int) substr($week, 4, 2);  // Extract and convert week number to integer
        
        // Calculate which quarter the week belongs to and define start and end weeks
        if ($weekNum <= 13) {
            $quarter = 'Q1';
            $startWeek = $year . '01';
            $endWeek = $year . '13';
        } elseif ($weekNum <= 26) {
            $quarter = 'Q2';
            $startWeek = $year . '14';
            $endWeek = $year . '26';
        } elseif ($weekNum <= 39) {
            $quarter = 'Q3';
            $startWeek = $year . '27';
            $endWeek = $year . '39';
        } else {
            $quarter = 'Q4';
            $startWeek = $year . '40';
            $endWeek = $year . '52';
        }
        
        $quarterLabel = $year . ' ' . $quarter; // Combine year and quarter
        
        // Save the available quarter with its start and end week
        if (!isset($quarters[$quarterLabel])) {
            $quarters[$quarterLabel] = [
                'startWeek' => $startWeek,
                'endWeek' => $endWeek
            ];
        }
    }
    
    return $quarters; // Return the calculated quarters with start and end weeks
}
	
	
	
	
}