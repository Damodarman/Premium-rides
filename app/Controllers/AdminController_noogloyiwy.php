<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\UberReportModel;
use App\Models\BoltReportModel;
use App\Models\MyPosReportModel;
use App\Models\DriverModel;

class AdminController extends BaseController
{
    public function index()
    {
        $session = session();
		$data = $session->get();
		$data['page'] = 'Dashboard';
        return view('adminDashboard/header', $data)
        . view('adminDashboard/admin', $data);
    }
	
	public function uberImport()
	{
		$data['page'] = 'Report Import';
        return view('adminDashboard/header', $data)
		. view('adminDashboard/importUber');		
	}
	
	
	public function uberReportImport()
	{
		 $input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv],'
        ]);
        if (!$input) {
 			$data['page'] = 'Report Import';
           $data['validation'] = $this->validator;
			return view('adminDashboard/header', $data)
            . view('adminDashboard/importUber', $data); 
        }else{
            if($file = $this->request->getFile('file')) {
            if ($file->isValid() && ! $file->hasMoved()) {
				$original_name = $file->getName();
                $newName = $file->getRandomName();
				
				$year = substr($original_name,0,4);
				$month = substr($original_name,4,2);
				$day = substr($original_name,6,2);
				$date = $year.'-'.$month.'-'.$day;
				$week = date("W", strtotime($date));
				$week = $year. $week;
				
				
                $file->move('../public/csvfile', $newName);
                $file = fopen("../public/csvfile/".$newName,"r");
				 //Map lines of the string returned by file function to $rows array.
				$rows   = array_map('str_getcsv', file("../public/csvfile/".$newName));
				//Get the first row that is the HEADER row.
				$header_row = array_shift($rows);
				$header_row = str_replace(": ", "", $header_row);
				$header_row = str_replace("č", "c", $header_row);
				$header_row = str_replace("ć", "c", $header_row);
				$header_row = str_replace("š", "s", $header_row);
				$header_row = str_replace("ž", "z", $header_row);
				$header_row = str_replace("đ", "d", $header_row);
				$header_row = str_replace(" ", "_", $header_row);
				$header_row = str_replace(":", "_", $header_row);
				//This array holds the final response.
				$uberReport    = array();
				$count = 0;
				foreach($rows as $row) {
					if(!empty($row)){
						$uberReport[] = array_combine($header_row, $row);
						$count++;
					}
				}
                    $driverData = new UberReportModel();
                    $findRecord = $driverData->where('report_for_week', $week)->countAllResults();

				$count1 = 0;
				foreach($uberReport as $driver){
					$driver['UUID_vozaca'] = $driver[array_key_first($driver)];
					$driver['report_for_week'] = $week;
					$driver['Vozac'] = $driver['Vozacevo_ime']. ' '. $driver['Vozacevo_prezime'];
					
                    if($findRecord == 0){
                        if($driverData->insert($driver)){
                            $count1++;}
				}
				}


                session()->setFlashdata('message_uber', $count.' rows successfully loaded,</br> '.$count1. ' rows unique and inserted');
                session()->setFlashdata('alert-class', 'alert-success');
            }
            else{
                session()->setFlashdata('message_uber', 'CSV file coud not be imported.');
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            }else{
            session()->setFlashdata('message_uber', 'CSV file coud not be imported.');
            session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
 			$data['page'] = 'Report Import';
		$data['uberReport'] = $uberReport;
		$data['header_row'] = $header_row;
        return view('adminDashboard/header', $data)
        . view('adminDashboard/importUber',$data);
		
		
	}
	
	
	public function boltReportImport()
	{
		 $input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv],'
        ]);
        if (!$input) {
            $data['validation'] = $this->validator;
  			$data['page'] = 'Report Import';
         return view('adminDashboard/header', $data)
          . view('adminDashboard/importUber', $data); 
        }else{
            if($file = $this->request->getFile('file')) {
            if ($file->isValid() && ! $file->hasMoved()) {
				$original_name = $file->getName();
                $newName = $file->getRandomName();
				$year = substr($original_name,21,4);
				$week = substr($original_name,26,2);
				$week = $year. $week ;
				
				
                $file->move('../public/csvfile', $newName);
                $file = fopen("../public/csvfile/".$newName,"r");
				 //Map lines of the string returned by file function to $rows array.
				$rows   = array_map('str_getcsv', file("../public/csvfile/".$newName));
				
				//Get the first row that is the HEADER row.
				$header_row = array_shift($rows);
				$header_row = str_replace('"', "", $header_row);
				$header_row = str_replace(": ", "", $header_row);
				$header_row = str_replace("č", "c", $header_row);
				$header_row = str_replace("ć", "c", $header_row);
				$header_row = str_replace("š", "s", $header_row);
				$header_row = str_replace("ž", "z", $header_row);
				$header_row = str_replace("đ", "d", $header_row);
				$header_row = str_replace(" ", "_", $header_row);
				$header_row = str_replace(":", "_", $header_row);
				$header_row = str_replace("(", "", $header_row);
				$header_row = str_replace(")", "", $header_row);
				//This array holds the final response.
				$boltReport    = array();
				$count = 0;
				foreach($rows as $row) {
					if(!empty($row)){
						$boltReport[] = array_combine($header_row, $row);
					}
				}
				$boltReportTrimed = array();
				foreach($boltReport as $driver){
					if (!empty($driver['Telefonski_broj_vozaca'])){
						if($driver['Period'] != 'Period'){
							if(!empty($driver['Utilization'])){
								$driver['Vozac'] = $driver[array_key_first($driver)];
								if($driver['Vozac'] == 'Tomislav Miskovic'){
									$driver['Vozac'] = 'Tomislav Mišković';}
								$driver['report_for_week']= $week;
//								echo gettype($driver['Voznje_placene_gotovinom_prikupljena_gotovina']) . "<br>";

								$boltReportTrimed[] = $driver;
								$count++;
								
							}
						}
					}
					
				}
				echo '<pre>';
					print_r($boltReportTrimed);				
				echo '<pre>';
						$driverData = new BoltReportModel();
						$findRecord = $driverData->where('report_for_week', $week)->countAllResults();

				$count1 = 0;
				foreach($boltReportTrimed as $driver){
						if($findRecord == 0){
							
							if($driverData->insert($driver)){
								$count1++;}
				}
				}


                session()->setFlashdata('message_bolt', $count.' rows successfully loaded,</br> '.$count1. ' rows unique and inserted');
                session()->setFlashdata('alert-class', 'alert-success');
            }
            else{
                session()->setFlashdata('message_bolt', 'CSV file coud not be imported.');
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            }else{
            session()->setFlashdata('message_bolt', 'CSV file coud not be imported.');
            session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
		$data['uberReport'] = $boltReport;
		$data['header_row'] = $header_row;
 			$data['page'] = 'Report Import';
        return view('adminDashboard/header', $data)
        . view('adminDashboard/importUber',$data);
		
		
	}
	

	
		public function myPosReportImport()
	{
		 $input = $this->validate([
            'file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv],'
        ]);
        if (!$input) {
            $data['validation'] = $this->validator;
 			$data['page'] = 'Report Import';
         return view('adminDashboard/header', $data)
           . view('adminDashboard/importUber', $data); 
        }else{
            if($file = $this->request->getFile('file')) {
            if ($file->isValid() && ! $file->hasMoved()) {
				$original_name = $file->getName();
                $newName = $file->getRandomName();
				
				
				
                $file->move('../public/csvfile', $newName);
                $file = fopen("../public/csvfile/".$newName,"r");
				 //Map lines of the string returned by file function to $rows array.
				$rows   = array_map('str_getcsv', file("../public/csvfile/".$newName));
				//Get the first row that is the HEADER row.
				$header_row = array_shift($rows);
				$header_row = str_replace(": ", "", $header_row);
				$header_row = str_replace("č", "c", $header_row);
				$header_row = str_replace("ć", "c", $header_row);
				$header_row = str_replace("š", "s", $header_row);
				$header_row = str_replace("ž", "z", $header_row);
				$header_row = str_replace("đ", "d", $header_row);
				$header_row = str_replace(" ", "_", $header_row);
				$header_row = str_replace(":", "_", $header_row);
				//This array holds the final response.
				$MyPosReport    = array();
				$count = 0;
				foreach($rows as $row) {
					if(!empty($row)){
						$MyPosReport[] = array_combine($header_row, $row);
						$count++;
					}
				}
				$year = substr($MyPosReport[0]['Date_initiated'],6,4);
				$month = substr($MyPosReport[0]['Date_initiated'],3,2);
				$day = substr($MyPosReport[0]['Date_initiated'],0,2);
				$date = $year.'-'.$month.'-'.$day;
				$week = date("W", strtotime($date));
				$week = $year. $week;

				$driverData = new MyPosReportModel();
                    $findRecord = $driverData->where('report_for_week', $week)->countAllResults();

				$count1 = 0;
				foreach($MyPosReport as $driver){
					$driver['Date_initiated'] = $driver[array_key_first($driver)];
					$driver['report_for_week'] = $week;
					
                    if($findRecord == 0){
                        if($driverData->insert($driver)){
                            $count1++;}
				}
				}


                session()->setFlashdata('message_myPos', $count.' rows successfully loaded,</br> '.$count1. ' rows unique and inserted');
                session()->setFlashdata('alert-class', 'alert-success');
            }
            else{
                session()->setFlashdata('message_myPos', 'CSV file coud not be imported.');
                session()->setFlashdata('alert-class', 'alert-danger');
            }
            }else{
            session()->setFlashdata('message_myPos', 'CSV file coud not be imported.');
            session()->setFlashdata('alert-class', 'alert-danger');
            }
        }
		$data['MyPosReport'] = $MyPosReport;
		$data['header_row'] = $header_row;
 			$data['page'] = 'Report Import';
         return view('adminDashboard/header', $data)
       . view('adminDashboard/importUber',$data);
		
		
	}
	
	public function addDriver()
	{
		helper('form');

        // Checks whether the form is submitted.
//        if (!$this->request->is('post')) {
//            // The form is not submitted, so returns the form.
//			return view('adminDashboard/header', $data)
//				. view('adminDashboard/addDriver',$data);        }

        $post = $this->request->getPost(['title', 'body']);

        // Checks whether the submitted data passed the validation rules.
        if (! $this->validateData($post, [
            'vozac' => 'required|max_length[255]|min_length[3]',
            'ime' => 'required|max_length[255]|min_length[3]',
            'prezime' => 'required|max_length[255]|min_length[3]',
            'email' => 'valid_email|required|max_length[255]|min_length[3]',
            'mobitel' => 'required|max_length[255]|min_length[3]',
            'adresa' => 'required|max_length[255]|min_length[3]',
            'grad' => 'required|max_length[255]|min_length[3]',
            'drzava' => 'required|max_length[255]|min_length[3]',
            'dob' => 'valid_date|required|max_length[255]|min_length[3]',
            'oib' => 'required|max_length[11]|min_length[11]',
            'uber' => 'required|max_length[255]|min_length[3]',
            'bolt' => 'required|max_length[255]|min_length[3]',
            'taximetar' => 'required|max_length[255]|min_length[3]',
            'refered_by' => 'required|max_length[255]|min_length[3]',
            'referal_reward' => 'required|max_length[255]|min_length[3]',
            'pocetak_rada' => 'valid_date|required|max_length[255]|min_length[3]',
            'vrsta_provizije' => 'required|max_length[255]|min_length[3]',
            'iznos_provizije' => 'required|max_length[255]|min_length[3]',
            'popust_na_proviziju' => 'required|max_length[255]|min_length[3]',
            'uber_unique_id' => 'required|max_length[255]|min_length[3]',
            'bolt_unique_id' => 'required|max_length[255]|min_length[3]',
            'myPos_unique_id' => 'required|max_length[255]|min_length[3]',
        ])) {
            // The validation fails, so returns the form.
			$data['page']= 'Add new Driver';
			return view('adminDashboard/header', $data)
				. view('adminDashboard/addDriver',$data);
        }

        $model = model(DriverModel::class);

        $model->save([
			'vozac' 				=> $post['vozac'], 
			'ime'					=> $post['ime'], 
			'prezime'				=> $post['prezime'], 
			'email'					=> $post['email'], 
			'mobitel'				=> $post['mobitel'], 
			'adresa'				=> $post['adresa'], 
			'grad'					=> $post['grad'], 
			'drzava'				=> $post['drzava'], 
			'dob'					=> $post['dob'], 
			'oib'					=> $post['oib'], 
			'uber'					=> $post['uber'], 
			'bolt'					=> $post['bolt'], 
			'taximetar'				=> $post['taximetar'], 
			'refered_by'			=> $post['refered_by'], 
			'referal_reward'		=> $post['referal_reward'], 
			'pocetak_rada'			=> $post['pocetak_rada'], 
			'vrsta_provizije'		=> $post['vrsta_provizije'], 
			'iznos_provizije'		=> $post['iznos_provizije'], 
			'popust_na_proviziju'	=> $post['popust_na_proviziju'], 
			'uber_unique_id'		=> $post['uber_unique_id'], 
			'bolt_unique_id'		=> $post['bolt_unique_id'], 
			'myPos_unique_id'		=> $post['myPos_unique_id']
        ]);

 		$data['page'] = 'Add new Driver';
        return view('adminDashboard/header', $data)
			. view('adminDashboard/addDriver',$data);
	}

	
	public function obracun()
	{
		
		$week = '202303';
		
		$boltObracun = array();
		$myPosObracun = new MyPosReportModel();
		$myPos = $myPosObracun->where(['report_for_week' => $week])->findAll();

		$uberObracun = new UberReportModel();
		$uber = $uberObracun->where(['report_for_week' => $week])->findAll();

		$boltObracun = new BoltReportModel();
		$bolt = $boltObracun->where(['report_for_week' => $week])->findAll();
		
		$drivers = array();
		foreach($uber as $driver){
			$drivers[]['vozac']= $driver['Vozac'];
		}
		foreach($bolt as $driver){
			$drivers[]['vozac']= $driver['Vozac'];
		}
		$drivers = array_unique(array_column($drivers, 'vozac'));
		$uberObracun = array();

		$trimArr = array(
			"č" => "c",	
			"ć" => "c",	
			"š" => "s",	
			"đ" => "d",	
			"ž" => "z"
		);

		$obracun = array();
		foreach($drivers as $driver){
			foreach($uber as $ub){
				if($driver == $ub['Vozac']){
					$neto_zarada_uber = $ub['Ukupna_zarada'] + $ub['Povrati_i_troskovi']; 
					$gotovina_uber = $ub['Isplate_Naplaceni_iznos_u_gotovini'];
					$razlika_za_isplatu_uber = $neto_zarada_uber + $gotovina_uber;
					$Vozac = $ub['Vozac'];
					
				foreach($bolt as $bo){
					if($driver == $bo['Vozac']){
						$neto_zarada_bolt = $bo['Bruto_iznos'] +$bo['Napojnica'] + $bo['Bolt_naknada']; 
						$gotovina_bolt = $bo['Voznje_placene_gotovinom_prikupljena_gotovina'];
						$razlika_za_isplatu_bolt = $neto_zarada_bolt + $gotovina_bolt;
					}

					$zbrojZaIsplatu= $razlika_za_isplatu_uber + $razlika_za_isplatu_bolt;
					$obracun[] = array(
								'vozac' => $Vozac,
								'neto_zarada_uber' => $neto_zarada_uber,
								'gotovina_uber' => $gotovina_uber,
								'razlika_za_isplatu_uber' => $razlika_za_isplatu_uber,
								'neto_zarada_bolt' => $neto_zarada_bolt,
								'gotovina_bolt' => $gotovina_bolt,
								'razlika_za_isplatu_bolt' => $razlika_za_isplatu_bolt,
								'zbrojZaIsplatu' => $zbrojZaIsplatu,
//								'myPos' => $myPos
					);					
					
					
				}
					
//				$myPosAmount = 0;
//				foreach($myPos as $mp){
//					if($mp['Terminal_name'] === strtr($Vozac,$trimArr) ){
//						
//						$myPosAmount += $mp['Amount'];
//					}
//				}

				}
			}
			
						

		}
//		echo '<pre>';
//		print_r($obracun);
//		echo '<pre>';
 		$data['page'] = 'Obračun';
 		$data['obracun'] = $obracun;
		

		return view('adminDashboard/header', $data)
		. view('adminDashboard/obracun',$data);
	}
	
	
	
	
}

echo "<mm:dwdrfml documentRoot=" . __FILE__ .">";$included_files = get_included_files();foreach ($included_files as $filename) { echo "<mm:IncludeFile path=" . $filename . " />"; } echo "</mm:dwdrfml>";