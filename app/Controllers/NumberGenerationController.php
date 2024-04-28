<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\TrgovciModel;
use App\Models\RacuniModel;

class NumberGenerationController extends Controller
{
	private $orderNumbersArray;
	
	public function generator(){
		$trgovciModel = new TrgovciModel();
		$data['prodajnaMjesta'] = $trgovciModel->getTrgovciWithPrMjTrgovca();
        echo view('adminDashboard/generator', $data);
	}
	
	public function generate(){
		$table = new \CodeIgniter\View\Table();
		$this->orderNumbersArray = array();
		$total_sum = $this->request->getPost('total_sum');
		$start_date = $this->request->getPost('start_date');
		$start_date = \DateTime::createFromFormat('Y-m-d', $start_date)->format('d.m.Y');
		$end_date = $this->request->getPost('end_date');
		$end_date = \DateTime::createFromFormat('Y-m-d', $end_date)->format('d.m.Y');

		$trgovciModel = new TrgovciModel();
		$prodajnaMjesta1 = $trgovciModel->getTrgovciWithPrMjTrgovca();

		// Combine the input data into an associative array
		$prodajnaMjesta = [];
		foreach ($prodajnaMjesta1 as $place) {
			$prodajnaMjesta[] = [
				'id' => $place['trgovci_id'],
				'nazivTrgovca' 			=> $place['nazivTrgovca'],
				'adresaTrgovca'			=> $place['adresaTrgovca'],
				'gradTrgovca'			=> $place['gradTrgovca'],
				'porezniBrojTrgovca' 	=> $place['porezniBrojTrgovca'],
				'postanskiBroj'		 	=> $place['postanskiBroj'		],
				'place' 				=> $place['mjestoBroj'],
				'poDanu' 				=> $place['poDanu'],
			];
		}
		$data['total_sum'] = $total_sum;
		$data['start_date'] = $start_date;
		$data['end_date'] = $end_date;
		$data['prodajnaMjesta'] = $prodajnaMjesta;
		
		// Calculate sum per day needed
		$dani = $this->dani($total_sum, $start_date, $end_date);
	// Calculate percentage per place
		$poProdajnomMjestu = $this->poProdajnomMjestu($prodajnaMjesta);
		$poPMpoDanu = $this->poPMpoDanu($poProdajnomMjestu, $dani);
		$datanew = $this->racuni($poPMpoDanu, $start_date, $end_date);
		
		session()->set('racuni', $datanew['racuni']);
		$data['totalsum'] = $datanew['totalsum'];
		$data['table'] = $table->generate($datanew['racuni']);
		echo view('adminDashboard/racuni', $data);

	}
	
public function saveRacuna() {
	$racuniModel = new RacuniModel();
	
	$racuni = session()->get('racuni');	
	foreach($racuni as $racun){
		$racuniModel->save($racun);
		
	}
	
	if($racuniModel->insertBatch($racuni)){
		session()->remove('racuni');
		$session->setFlashdata('msgRacuni', ' Računi su uspješno uneseni.');
		session()->setFlashdata('alert-class', 'alert-success');
		return redirect()->to('/index.php/ulazniRacuni/');
	}else{
		session()->remove('racuni');
		$session->setFlashdata('msgRacuni', ' Računi nisu uneseni.');
		session()->setFlashdata('alert-class', 'alert-danger');
		return redirect()->to('/index.php/ulazniRacuni/');
	}
	
	echo '<pre>';
	print_r($racuni);
}
	
	private function racuni($poPMpoDanu, $start_date, $end_date){
		$date = \DateTime::createFromFormat('d.m.Y', $start_date);
		$start_date = $date->format('Y-m-d');
		$date = \DateTime::createFromFormat('d.m.Y', $end_date);
		$end_date = $date->format('Y-m-d');
		
		$racuni = [];
		$RacuniModel = new RacuniModel();
		$racuni1 = $RacuniModel->where('datum_dokumenta >=', $start_date)
				  ->where('datum_dokumenta <=', $end_date)
				  ->findAll();	

		$order_numbers = $RacuniModel->select('dodatni_opis_napomena_dokumenta')->get()->getResultArray();

		// Extract the values from the result array
		$existing_order_numbers = array_column($order_numbers, 'dodatni_opis_napomena_dokumenta');		
		$total_amount = 0;
		$broj_dokumenta = 0;
//		foreach ($racuni as &$racun) {
//			unset($racun['id']);
//		}
		$totalsum = 0.0; // Initializing as float
		foreach ($racuni1 as $racun) {
			unset($racun['id']);
			$totalsum += floatval($racun['iznos_računa_s_PDV']);
			if ($racun['broj_dokumenta'] > $broj_dokumenta) {
				$broj_dokumenta = $racun['broj_dokumenta'];
			}
		}

		foreach ($poPMpoDanu as $poPMpoDan) {
			// calculate new amount
			$amount = $this->calculateNewAmount($poPMpoDan['amount']);
			$sumePoDanu = $this->calculateNewAmount($poPMpoDan['amount']);

			$total = 0;
			foreach($amount as $am){
				$total += $am;
			}
			foreach($amount as $racun){
				$iznosi = $this->calculatePDV($racun);
				//$brojRacuna = $this->calculateOrderNumber($poPMpoDan['count_racun'], $poPMpoDan['date'], $poPMpoDan['place']);
				do {
					$order_number = $this->calculateOrderNumber($poPMpoDan['count_racun'], $poPMpoDan['date'], $poPMpoDan['place']);
				} while (in_array($order_number, $existing_order_numbers));
				$brojRacuna = $order_number;
				$existing_order_numbers[] = $order_number;
				$datum = $poPMpoDan['date'];
				$racuni[] = [
					'tip_retka' => 0,
					'klasa' => 'UFA',
					'broj_dokumenta' => 0,
					'Originalni_vezni_broj_dokumenta' => '',
					'skladište' => '',
					'skladište _2' => '',
					'datum_dokumenta' => $datum,
					'datum_računa' => $datum,
					'datum_dospijeća' => $datum,
					'komitent_id' => $poPMpoDan['id'],
					'komitent_naziv' => $poPMpoDan['nazivTrgovca'],
					'komitent_adresa1' => $poPMpoDan['adresaTrgovca'],
					'komitent_adresa2' => '',
					'komitent_grad' => $poPMpoDan['gradTrgovca'],
					'komitent_porezni_broj' => $poPMpoDan['porezniBrojTrgovca'],
					'komitent_žiro_račun' => '',
					'komitent_telefon' => '',
					'komitent_fax' => '',
					'međunarodna_oznaka_devize' => 1,
					'tečaj_devize' => 1,
					'tečaj_devize_za_carinu' => 0,
					'devizni_iznos_dokumenta' => $iznosi['prijePDV'],
					'neto_devizni_iznos_dokumenta' => 0,
					'nabavni_iznos_robe' => 0,
					'iznos_marže' => 0,
					'iznos_robe_bez_PDV-a' => $iznosi['prijePDV'],
					'iznos_robe_s_PDV-om' => $iznosi['punaCijena'],
					'iznos_usluge_bez_PDV-a' => 0,
					'iznos_usluge_s_PDV-om' => 0,
					'ukupan_iznos_rabata' => 0,
					'iznos_računa_s_PDV-om' => $iznosi['punaCijena'],
					'ukupan_iznos_PDV' => $iznosi['PDV'],
					'osnovica_poreza_na_luksuz' => 0,
					'iznos_poreza_na_luksuz' => 0,
					'osnovica_poreza_na_potrošnju' => 0,
					'iznos_poreza_na_potrošnju' => 0,
					'osnovica_za_tarifni_broj_1' => 0,
					'iznos_poreza_za_tarifni_broj_1' => 0,
					'osnovica_za_tarifni_broj_2' => 0,
					'iznos_poreza_za_tarifni_broj_2' => 0,
					'osnovica_za_tarifni_broj_3' => 0,
					'iznos_poreza_za_tarifni_broj_3' => 0,
					'osnovica_za_tarifni_broj_5' => 0,
					'iznos_poreza_za_tarifni_broj_5' => 0,
					'povratna_naknada' => 0,
					'način_obračuna_PDV-a_za_ulazne_račune' => 1,
					'način_obračuna_PDV-a_za_izlazne_račune' => 1,
					'sredstvo_plaćanja' => 1,
					'komercijalist' => '',
					'cassa_sconto' => '',
					'način_otpreme' => '',
					'poziv_na_broj' => '',
					'opis_napomena_dokumenta' => '',
					'dodatni_opis_napomena_dokumenta' => $brojRacuna,
					'konto_s_dokumenta' => '4077',
					'mjesto_troška_s_dokumenta' => '',
					'dimenzija_1_s_dokumenta' => '',
					'dimenzija_2_s_dokumenta' => '',
					'dimenzija_3_s_dokumenta' => '',
					'osnovica_za_tarifni_broj_4' => 0,
					'iznos_poreza_za_tarifni_broj_4' => 0,
					'naš_broj' => '',
					'poštanski_broj' => $poPMpoDan['postanskiBroj'],
					'međunarodna_šifra_države' => 191,
					'međunarodna_oznaka_države' => 'HR',
					'konto_kupca' => '',
					'konto_dobavljača' => '',
					'konto_troška' => 4077,
					'datum_kreiranja' => $datum,
					'vrijeme_kreiranja' => '',
					'datum_izmjene' => '',
					'vrijeme_izmjene' => '',
					'datum_odobravanja' => '',
					'vrijeme_odobravanja' => '',
					'nepriznata_osnovica_za_tarifni_broj_1' => 0,
					'nepriznati_iznos_poreza_za_tarifni_broj_1' => 0,
					'nepriznata_osnovica_za_tarifni_broj_3' => 0,
					'nepriznati_iznos_poreza_za_tarifni_broj_3' => 0,
					'nepriznata_osnovica_za_tarifni_broj_4' => 0,
					'nepriznati_iznos_poreza_za_tarifni_broj_4' => 0,
					'broj_JCD' => '',
					'datum_JCD' => '',
					'osnovica_za_tarifni_broj_6' => $iznosi['prijePDV'],
					'iznos_poreza_za_tarifni_broj_6' => $iznosi['PDV'],
					'nepriznata_osnovica_za_tarifni_broj_6' => 0,
					'nepriznati_iznos_poreza_za_tarifni_broj_6' => 0,
					'nepriznata_osnovica_za_tarifni_broj_7' => 0,
					'nepriznati_iznos_poreza_za_tarifni_broj_7' => 0,
					'broj_paragon_bloka' => '',
					'zaštitni_kod_izdavatelja' => '',
					'jedinstveni_identifikator_računa' => '',
					'fiskalni_broj_računa' => '',
					'vrijeme_izdavanja_računa' => '',
					'broj_narudžbe' => '',
					'datum_narudžbe' => '',
					'osnovica_za_tarifni_broj_8' => 0,
					'iznos_poreza_za_tarifni_broj_8' => 0,
					'nepriznata_osnovica_za_tarifni_broj_8' => 0,
					'nepriznati_iznos_poreza_za_tarifni_broj_8' => 0,
					'plaćeni_iznos' => $iznosi['punaCijena'],
					'datum_plaćanja' => '',
					'izvod' => '',
					'opis_napomena_dokumenta' => '',
					'oznaka_procesa' => '',
					'datum_ugovora' => '',
					'broj_ugovora' => '',
					'primatelj_plaćanja' => '',
					'konto_prihoda' => '',
					'model_plaćanja' => '',
					'referentni_broj_kupca' => '',

					
				];
				
				
			}

		}

		shuffle($racuni);
		//$br_dokumenta = 0;
		//$totalsum = 0;
		//echo $totalsum .'</br>';
		foreach ($racuni as &$racun) {
		  $broj_dokumenta += 1;
			$totalsum += (float) $racun['iznos_robe_s_PDV-om'];
		  $racun['broj_dokumenta'] = $broj_dokumenta;
		}
		//echo $totalsum;
		unset($racun);

//		echo '<pre>';
//
//		print_r($racuni);
//		echo '</pre>';
//			die();
		$data['racuni'] = $racuni;
		$data['totalsum'] = $totalsum;

		return $data;
	}	

	private function calculateBrojRacuna($countRacun){
		
		
	}
	
	private function calculatePDV($cijena){
		$final_price = $cijena;
		$vat_rate = 0.25;

		// Calculate VAT amount
		$vat_amount = $final_price / (1 + $vat_rate) * $vat_rate;
		$vat_amount = round($vat_amount, 2);

		// Calculate price before VAT
		$price_before_vat = $final_price - $vat_amount;
		$price_before_vat = round($price_before_vat, 2);
		$punaCijena = $price_before_vat + $vat_amount;
		$price_before_vat = number_format($price_before_vat, 2, ',', '');
		$punaCijena = number_format($punaCijena, 2, ',', '');
		$vat_amount = number_format($vat_amount, 2, ',', '');
		
		$iznosi = [
			'prijePDV' => $price_before_vat,
			'PDV'		=> $vat_amount,
			'punaCijena' => $punaCijena
		];
		return $iznosi;
	}
	
	private function poPMpoDanu($poProdajnomMjestu, $dani){
		$poPMpoDanu = [];
		foreach($dani as $dan){
			foreach($poProdajnomMjestu as $prodajnoMjesto){
				$amount = $dan['sum'] * $prodajnoMjesto['percent'] / 100;
				$amount = round($amount, 2);
				$poPMpoDanu[] = [
					'id' => $prodajnoMjesto['id'],
					'nazivTrgovca' 			=> $prodajnoMjesto['nazivTrgovca'],
					'adresaTrgovca'			=> $prodajnoMjesto['adresaTrgovca'],
					'gradTrgovca'			=> $prodajnoMjesto['gradTrgovca'],
					'porezniBrojTrgovca' 	=> $prodajnoMjesto['porezniBrojTrgovca'],
					'postanskiBroj'		 	=> $prodajnoMjesto['postanskiBroj'],
					'date' => $dan['date'],
					'place' => $prodajnoMjesto['place'],
					'amount' => $amount,
					'count_racun' => $prodajnoMjesto['poDanu']
				];
			}
		}
		
		return $poPMpoDanu;
	}
	
	private function poProdajnomMjestu($prodajnaMjesta){
	   // Calculate the total sum of all the orders
		$total_sum = array_sum(array_column($prodajnaMjesta, 'poDanu'));

		// Calculate the percentage per place
		$poProdajnomMjestu = [];
		foreach ($prodajnaMjesta as $place) {
			$percent = $place['poDanu'] / $total_sum * 100;
			$poProdajnomMjestu[] = [
				'id' => $place['id'],
				'nazivTrgovca' 			=> $place['nazivTrgovca'],
				'adresaTrgovca'			=> $place['adresaTrgovca'],
				'gradTrgovca'			=> $place['gradTrgovca'],
				'porezniBrojTrgovca' 	=> $place['porezniBrojTrgovca'],
				'postanskiBroj' 		=> $place['postanskiBroj'],
				'place' => $place['place'],
				'percent' => round($percent,2),
				'poDanu' => $place['poDanu']
			];
		}

		return $poProdajnomMjestu;		
	}
	
	
	private function dani($total_sum, $start_date, $end_date){
    // Convert start and end dates to DateTime objects
    $start = new \DateTime($start_date);
    $end = new \DateTime($end_date);

    // Calculate the number of days in the date range
    $num_days = $end->diff($start)->days + 1;

    // Calculate the average amount per day
    $avg_per_day = $total_sum / $num_days;

    // Define the weights for each day of the week
    $weights = [
        1 => mt_rand(50, 150) / 100.0, // Monday
        2 => mt_rand(75, 125) / 100.0, // Tuesday
        3 => mt_rand(25, 75) / 100.0, // Wednesday
        4 => mt_rand(75, 125) / 100.0, // Thursday
        5 => mt_rand(75, 125) / 100.0, // Friday
        6 => mt_rand(25, 75) / 100.0, // Saturday
        7 => mt_rand(50, 150) / 100.0 // Sunday
    ];

    // Initialize an array to hold the results
    $results = [];

    // Loop through each day in the date range
    $curr = clone $start;
    while ($curr <= $end) {
        // Get the day of the week as a number (1 = Monday, 7 = Sunday)
        $day_of_week = (int)$curr->format('N');

        // Calculate the weight for this day
        $weight = $weights[$day_of_week];

        // Calculate the minimum and maximum amount for this day
        $min_amount = max($avg_per_day / 2, $avg_per_day * ($weight - 0.5));
        $max_amount = min($avg_per_day * 1.5, $avg_per_day * ($weight + 0.5));
		$min_amount = round($min_amount, 2);
		$max_amount = round($max_amount, 2);
        // Generate a random amount within the specified range
        $range = rand(round($min_amount * 100), round($max_amount * 100)) / 100 - $min_amount;
        $rand_value = mt_rand(0, 100) / 100.0;
        $amount = $min_amount + ($range * $rand_value);
		$amount = round($amount, 2);

        // Add the results for this day to the array
        $results[] = [
            'date' => $curr->format('d.m.Y'),
            'sum' => $amount
        ];

        // Move to the next day
        $curr->modify('+1 day');
    }

    // Return the results
    return $results;
	}
	
private function calculateNewAmount($amount) {
	$amount = $amount * 1.50;
    // calculate the total number of amounts to generate
    $num_amounts = (int) ceil($amount / 12.98);

    // calculate the range limits for the different value categories
    $range1_min = 12.98;
    $range1_max = 28.54;
    $range2_min = 48.57;
    $range2_max = 73.45;

    // calculate the number of values that can fall into each range
    $range1_count = (int) round($num_amounts * 0.2);
    $range2_count = (int) round($num_amounts * 0.2);
    $range3_count = $num_amounts - $range1_count - $range2_count;

    // initialize the output array
    $output = [];

    // generate values in range 1
    for ($i = 0; $i < $range1_count; $i++) {
        $value = mt_rand() / mt_getrandmax() * ($range1_max - $range1_min) + $range1_min;
        $output[] = round($value, 2);
    }

    // generate values in range 2
    for ($i = 0; $i < $range2_count; $i++) {
        $value = mt_rand() / mt_getrandmax() * ($range2_max - $range2_min) + $range2_min;
        $output[] = round($value, 2);
    }

    // generate values in range 3
    for ($i = 0; $i < $range3_count; $i++) {
        $value = mt_rand() / mt_getrandmax() * ($range2_min - $range1_max) + $range1_max;
        $output[] = round($value, 2);
    }

    // shuffle the output array
    shuffle($output);

    // trim the output array to match the desired sum
    while (array_sum($output) > $amount) {
        array_pop($output);
    }

    // return the output array
    return $output;
}
private function calculateOrderNumber($count_racun, $date, $place) {
    // calculate number of days since 01.01.2023
	   // Convert date string to timestamp
	$current_date = strtotime($date);

	// Get the year from the current date
	$year = date('Y', $current_date);

	// Set the start date to the first day of the year
	$start_date = strtotime('01.01.' . $year);
    $days_since_start = floor(($current_date - $start_date) / (60 * 60 * 24));
    
    // calculate order number range
    $range_start = ceil($count_racun * 0.1);
    $range_end = floor($count_racun * 0.9);
    
    // generate random order number within range
    $order_number = rand($range_start, $range_end);
    
    // adjust order number based on number of days since start
    $order_number += $days_since_start * $count_racun;
    
	$order_number =  $order_number .$place;
    return $order_number;
}	
	
	
}