<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\DriverModel;
use App\Models\ObracunModel;
use App\Models\FiskalizacijaModel;
use App\Models\ObracunFirmaModel;
use App\Models\FlotaModel;
use App\Models\ReferalBonusModel;
use App\Models\VozilaModel;
use App\Models\DoprinosiModel;
use App\Models\UberReportModel;
use App\Models\BoltReportModel;
use App\Models\MyPosReportModel;
use App\Models\DugoviModel;
use App\Models\TaximetarReportModel;
use App\Models\ActivityUberModel;
use App\Models\BoltDriverActivityModel;

//ini_set('post_max_size', '64M');   // Change to the desired size
//ini_set('max_input_vars', 2000);   // Change to the desired maximum number of input variables




class ObracunController extends BaseController
{
	public function index(){
		$session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');

		$UberReportModel = new UberReportModel();
		$BoltReportModel = new BoltReportModel();
		$ObracunModel = new ObracunModel();
		$ObracunFirmaModel = new ObracunFirmaModel();
		
		$weeks = $BoltReportModel->select('report_for_week')->where('fleet', $fleet)->get()->getResultArray();
		foreach($weeks as $we){$reportForWeek[] = $we['report_for_week'];}
		$reportForWeek = array_unique($reportForWeek);
		
		$dostupnoZaObracun = array();
		// Provjeravamo dali postoji gotov obračun
		foreach($reportForWeek as $week){
			$zbrojObracuna = $ObracunModel->where('fleet', $fleet)->where('week', $week)->countAllResults();
			if($zbrojObracuna > 0){
				$obracun = $ObracunFirmaModel->where('fleet', $fleet)->where('week', $week)->get()->getRowArray();
				$gotoviObracuni[] = $obracun;
				$data['gotoviObracuni'] = $gotoviObracuni;
			}
			if(!$zbrojObracuna > 0){
				$dostupnoZaObracun[] = array('week' => $week);
			}
		}
		
		$data['dostupnoZaObracun'] = $dostupnoZaObracun;
		$data['page'] = 'Obracuni';
		$data['fleet'] = $fleet;
			
		return view('adminDashboard/header', $data)
		. view('adminDashboard/navBar')
		. view('adminDashboard/obracun')
			. view('footer');
	}
	
	public function obracunDelete($week = null){
		$session = session();
		$fleet = $session->get('fleet');
		
		$obracunModel = new ObracunModel();
		$dugoviModel = new DugoviModel();
		$obracunFirmaModel = new ObracunFirmaModel();
		$referalBonusModel = new ReferalBonusModel();
		$fiskalizacijaModel = new FiskalizacijaModel();
		
		$obracunFirmadelete = $obracunFirmaModel->where('fleet', $fleet)->where('week', $week)->delete();
		$obracunDelete = $obracunModel->where('fleet', $fleet)->where('week', $week)->delete();
		$dugoviDelete = $dugoviModel->where('fleet', $fleet)->where('week', $week)->delete();
		$referalBonusDelete = $referalBonusModel->where('fleet', $fleet)->where('week', $week)->delete();
		
		$session->setFlashdata('obracunDelete', ' Obračun za ' .$week .' je obrisan');
		session()->setFlashdata('alert-class', 'alert-dark');
		return redirect()->to('obr');
	}
	
	public function getPlaceData(){
		$session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$weeks = $this->request->getVar('weeks');
		print_r($weeks);
		die();
		
		
		return view('adminDashboard/header', $data)
		. view('adminDashboard/navBar')
		. view('adminDashboard/placeForm')
			. view('footer');
		
	}
	
	
	public function place(){
		
		$session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$ObracunModel = new ObracunModel();
		$ObracunFirmaModel = new ObracunFirmaModel();
		
		$weeks = $ObracunFirmaModel->select('week')->where('fleet', $fleet)->get()->getResultArray();
		
		$data['page'] = 'Izvoz plaća';
		$data['fleet'] = $fleet;
		$data['weeks'] = $weeks;
		$data['role'] = $role;
			
		return view('adminDashboard/header', $data)
		. view('adminDashboard/navBar')
		. view('adminDashboard/placeForm')
			. view('footer');
	}	
	
	public function petiTjedan($vozac_id){

	}
	
	public function obracunaj(){
		
		$week = $this->request->getPost('wkN');
		$ptj = $this->request->getPost('ptj');
		
		if(isset($ptj)){
			$petiTjedan = true;
		}else{
			$petiTjedan = false;
		}
		
		
		
		helper(['form']);
		$session = session();
		$fleet = $session->get('fleet');
		// get all drivers
		$drivers = $this->getDrivers();
		// Koji je mjesec za fiskalizaciju
		$yearNo = substr($week, 0, 4);
		$weekNo = substr($week, 4, 2);
		$date=date_create();
		date_isodate_set($date,$yearNo,$weekNo);
		$monthNo = date_format($date,"m");
		$fiskMonthNo = date_format($date,"Ym");
		
		$naPlacu = $drivers['naPlacu'];
		$naProviziju = $drivers['naProviziju'];
		$obracunNaProviziju = array();
		
		$fiskalizacijaModel = new FiskalizacijaModel();
		$flotaModel = new FlotaModel();
		$vozilaModel = new VozilaModel();
		$postavkeFlote = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
		$koristi_activity = $postavkeFlote['koristi_activity'];
		
		// Ako ima vozaca na proviziju
		if(!empty($naProviziju)){
			$data['naProviziju'] = $naProviziju;
				$firmaObracun['week'] = $week;
				$firmaObracun['fleet'] = $fleet;
				$firmaObracun['uberNeto'] = 0;
				$firmaObracun['uberGotovina'] = 0;
				$firmaObracun['uberNapojnica'] = 0;
				$firmaObracun['uberPovrat'] = 0;
				$firmaObracun['uberRazlika'] = 0;
				$firmaObracun['boltNeto'] = 0;
				$firmaObracun['boltNapojnica'] = 0;
				$firmaObracun['boltPovrati'] = 0;
				$firmaObracun['boltGotovina'] = 0;
				$firmaObracun['boltRazlika'] = 0;
				$firmaObracun['ukupnoRazlikaSvi'] = 0;
				$firmaObracun['zaIsplatu'] = 0;
				$firmaObracun['myPosNeto'] = 0;
				$firmaObracun['myPosNapojnica'] = 0;
				$firmaObracun['myPosPovrati'] = 0;
				$firmaObracun['myPosRazlika'] = 0;
				$firmaObracun['myPosGotovina'] = 0;
				$firmaObracun['provizija'] = 0;
				$firmaObracun['taximetar'] = 0;
				$firmaObracun['activity'] = 0;
				$firmaObracun['doprinosi'] = 0;
				$firmaObracun['netoPlaca'] = 0;
				$firmaObracun['refBonus'] = 0;
				$firmaObracun['fiskalizacijaUber'] = 0;
				$firmaObracun['fiskalizacijaBolt'] = 0;
			 	$firmaObracun['taximetarNeto'] = 0;
				$firmaObracun['ukupnoNetoSvi'] = 0;
				$firmaObracun['ukupnoNapojnicaSvi'] = 0;
				$firmaObracun['ukupnoPovratiSvi'] = 0;
				$firmaObracun['ukupnoGotovinaSvi'] = 0;
			foreach($naProviziju as $dr){
				$vozilo = $vozilaModel->where('vozac_id', $dr['id'])->get()->getRowArray();
				if(isset($vozilo)){
					if($vozilo['placa_firma'] != 0 ){
						$driverObracun['najamVozila'] = $vozilo['cijena_tjedno'];
					}else{
						$driverObracun['najamVozila'] = 0;
					}
				}else{
					$driverObracun['najamVozila'] = 0;
				}
				$driverObracun['vozac_id'] = $dr['id']; 
				$driverObracun['vozac'] = $dr['vozac']; 
				$driverObracun['uberNeto'] = 0; 
				$driverObracun['uberNapojnica'] = 0; 
				$driverObracun['uberPovrat'] = 0; 
				$driverObracun['uberGotovina'] = 0; 
				$driverObracun['uberRazlika'] = 0; 
				$driverObracun['boltNeto'] = 0; 
				$driverObracun['boltNapojnica'] = 0; 
				$driverObracun['boltPovrati'] = 0; 
				$driverObracun['boltGotovina'] = 0; 
				$driverObracun['boltRazlika'] = 0; 
				$driverObracun['ukupnoRazlika'] = 0; 
				$driverObracun['zaIsplatu'] = 0; 
				$driverObracun['myPosNeto'] = 0; 
				$driverObracun['myPosNapojnica'] = 0; 
				$driverObracun['myPosPovrati'] = 0; 
				$driverObracun['myPosGotovina'] = 0; 
				$driverObracun['myPosRazlika'] = 0; 
				$driverObracun['myPosTransakcije'] = 0; 
				$driverObracun['aktivan'] = $dr['aktivan'];
				$driverObracun['week'] = $week;
				$driverObracun['isplataNa'] = $dr['tjedna_isplata'];
				$driverObracun['IBAN'] = $dr['IBAN'];
				$driverObracun['fleet'] = $fleet;
				$vozac['week'] = $week;
				$vozac['vozac'] = $dr['vozac'];;
				$vozac['pocetak_rada'] = $dr['pocetak_rada'];
				$vozac['UUID_vozaca'] = $dr['UUID_vozaca'];
				$vozac['tel_broj'] = $dr['mobitel'];
				$vozac['dob'] = $dr['dob'];
				$vozac['sezona'] = $dr['sezona'];
				$vozac['vozac_id'] = $dr['id'];
				$vozac['taximetar_unique_id'] = $dr['taximetar_unique_id'];
				$vozac['provizijaNaljepnice'] = $dr['provizijaNaljepnice'];
				$vozac['vrsta_provizije'] = $dr['vrsta_provizije'];
				$vozac['iznos_provizije'] = $dr['iznos_provizije'];
				$vozac['popust_na_proviziju'] = $dr['popust_na_proviziju'];
				$vozac['uber_unique_id'] = $dr['uber_unique_id'];
				$vozac['bolt_unique_id'] = $dr['bolt_unique_id'];
				$vozac['myPos_unique_id'] = $dr['myPos_unique_id'];
				$vozac['pocetak_prijave'] = $dr['pocetak_prijave'];
				$vozac['referal_reward'] = $dr['referal_reward'];
				$razlikaDana = $this->daniRada($vozac);
				$driverObracun['age'] = $razlikaDana['age'];
				$driverObracun['dani_rada'] = $razlikaDana['dani_rada'];
				$vozac['age'] = $razlikaDana['age'];
				$vozac['daniRada'] = $razlikaDana['dani_rada'];
				$vozac['daniPrijave'] = $razlikaDana['dani_prijave'];
				$wEMonth = $razlikaDana['wEMonth'];
				$wSMonth = $razlikaDana['wSMonth'];
				$uberFiskalizacija = 0;
				$boltFiskalizacija = 0;
				
				//Ako ima UBER povuci report za uber
					if($dr['uber'] != 0){
						$report = $this->getUberReport($vozac);

						$driverObracun['uberNeto'] = $report['uberNeto']; 
						$driverObracun['uberNapojnica'] = $report['uberNapojnica']; 
						$driverObracun['uberPovrat'] = $report['uberPovrat']; 
						$driverObracun['uberGotovina'] = $report['uberGotovina'];
						$driverObracun['uberRazlika'] =  $report['uberNeto'] + $report['uberPovrat'] + $report['uberNapojnica'] + $report['uberGotovina']; 
						$firmaObracun['uberNeto'] += $report['uberNeto']; ;
						$firmaObracun['uberGotovina'] += $report['uberGotovina'];
						$firmaObracun['uberNapojnica'] += $report['uberNapojnica'];
						$firmaObracun['uberPovrat'] += $report['uberPovrat'];
						$firmaObracun['uberRazlika'] += $driverObracun['uberRazlika'];
						if($report['uberNeto'] != 0){
							$uberFiskalizacija = 1;
						}
						
					}
					//Ako ima BOLT povuci report za bolt
					if($dr['bolt'] != 0){
						$report = $this->getBoltReport($vozac);
						if(!empty($report)){
							$driverObracun['boltNeto'] = $report['boltNeto']; 
							$driverObracun['boltNapojnica'] = $report['boltNapojnica']; 
							$driverObracun['boltPovrati'] = $report['boltPovrati']; 
							$driverObracun['boltGotovina'] = $report['boltGotovina']; 
							$driverObracun['boltNaljepnice'] = $report['boltNaljepnice']; 
							$driverObracun['boltRazlika'] = $report['boltNeto']+$report['boltPovrati']+$report['boltGotovina'] + $report['boltNapojnica']; 
							$firmaObracun['boltNeto'] += $report['boltNeto'] + $report['boltNaljepnice']; 
							$firmaObracun['boltNapojnica'] += $report['boltNapojnica']; 
							$firmaObracun['boltPovrati'] += $report['boltPovrati']; 
							$firmaObracun['boltGotovina'] += $report['boltGotovina']; 
							$firmaObracun['boltRazlika'] += $driverObracun['boltRazlika']; 
							if($report['boltNeto'] != 0){
								$boltFiskalizacija = 1;
							}
								
							}else{
							$driverObracun['boltNeto'] = 0; 
							$driverObracun['boltNapojnica'] = 0; 
							$driverObracun['boltPovrati'] = 0; 
							$driverObracun['boltGotovina'] = 0; 
							$driverObracun['boltNaljepnice'] = 0; 
							$driverObracun['boltRazlika'] = 0; 
						}
						
					}else{
							$driverObracun['boltNeto'] = 0; 
							$driverObracun['boltNapojnica'] = 0; 
							$driverObracun['boltPovrati'] = 0; 
							$driverObracun['boltGotovina'] = 0; 
							$driverObracun['boltNaljepnice'] = 0; 
							$driverObracun['boltRazlika'] = 0; 
					}
					//Ako ima Taximetar povuci report za Taximetar
					if($dr['taximetar'] == '1'){
						$report = $this->getTaximetarReport($vozac);
						$driverObracun['taximetarNeto'] = $report['taximetarNeto'];
						$firmaObracun['taximetarNeto'] += $report['taximetarNeto']; 
					}else{
						$driverObracun['taximetarNeto'] = 0;
					}
					//Ako ima MyPos povuci report za MyPos
					if($dr['myPos'] == '1'){
						$report = $this->getMyPosReport($vozac);
						$myPosGotovina = $driverObracun['taximetarNeto'] - $report['myPosNeto'];
						$myPosGotovina = -1 * $myPosGotovina;
						$driverObracun['myPosNeto'] = $driverObracun['taximetarNeto']; 
						$firmaObracun['myPosNeto'] += $driverObracun['taximetarNeto']; 
						$driverObracun['myPosNapojnica'] = 0; 
						$driverObracun['myPosPovrati'] = 0; 
						$driverObracun['myPosGotovina'] = $myPosGotovina; 
						
						$driverObracun['myPosTransakcije'] = $report['myPosTransakcije']; 
						$driverObracun['myPosRazlika'] = $driverObracun['taximetarNeto'] + $myPosGotovina; 
						
					}else{
						$myPosGotovina = $driverObracun['taximetarNeto'];
						$myPosGotovina = -1 * $myPosGotovina;
						$driverObracun['myPosNeto'] = $driverObracun['taximetarNeto']; 
						$firmaObracun['myPosNeto'] += $driverObracun['taximetarNeto']; 
						$driverObracun['myPosNapojnica'] = 0; 
						$driverObracun['myPosPovrati'] = 0; 
						$driverObracun['myPosGotovina'] = $myPosGotovina; 
						
						$driverObracun['myPosRazlika'] = 0; 
					}
				$ukupnoNeto = $driverObracun['boltNeto'] + $driverObracun['uberNeto'];
				$vozac['ukupnoNeto'] = $ukupnoNeto;
				$vozac['taximetarNeto'] = $driverObracun['taximetarNeto'];
				$vozac['boltNaljepnice'] = $driverObracun['boltNaljepnice'];
				$driverObracun['ukupnoNeto'] = $driverObracun['myPosNeto'] + $driverObracun['boltNeto'] + $driverObracun['uberNeto'];
				$driverObracun['ukupnoNapojnica'] = $driverObracun['boltNapojnica'] + $driverObracun['uberNapojnica'];
				$driverObracun['ukupnoPovrati'] = $driverObracun['boltPovrati'] + $driverObracun['uberPovrat'];
				$driverObracun['ukupnoGotovina'] = $driverObracun['boltGotovina'] + $driverObracun['uberGotovina'] + $driverObracun['myPosGotovina'];
				$driverObracun['ukupnoRazlika'] = $driverObracun['ukupnoNeto'] + $driverObracun['ukupnoNapojnica'] + $driverObracun['ukupnoPovrati'] + $driverObracun['ukupnoGotovina'];
				$firmaObracun['ukupnoNetoSvi'] += $driverObracun['ukupnoNeto'];
				$firmaObracun['ukupnoNapojnicaSvi'] += $driverObracun['ukupnoNapojnica'];
				$firmaObracun['ukupnoPovratiSvi'] += $driverObracun['ukupnoPovrati'];
				$firmaObracun['ukupnoGotovinaSvi'] += $driverObracun['ukupnoGotovina'];
				$firmaObracun['ukupnoRazlikaSvi'] += $driverObracun['ukupnoRazlika'];
				
				
				
				
				if($koristi_activity != 0){
					$bUId = $vozac['bolt_unique_id'];
					$uUId = $vozac['uber_unique_id'];
					$activity = $this->izracunajActivity($bUId, $uUId, $week);
					$uberOnline = $activity['uberOnline'];
					$uberActiv = $activity['uberActiv'];
					$boltOnline = $activity['boltOnline'];
					$boltActiv = $activity['boltActiv'];
					$uberPerH = ($uberActiv != 0) ? round($driverObracun['uberNeto'] / $uberActiv, 2) : 0;
					$boltPerH = ($boltActiv != 0) ? round($driverObracun['boltNeto'] / $boltActiv, 2) : 0;
					$uberPerOH = ($uberOnline != 0) ? round($driverObracun['uberNeto'] / $uberOnline, 2) : 0;
					$boltPerOH = ($boltOnline != 0) ? round($driverObracun['boltNeto'] / $boltOnline, 2) : 0;
					$totalOnline = $uberOnline + $boltOnline;
					$totalActiv = $uberActiv + $boltActiv;
					$totNeto = $driverObracun['uberNeto'] + $driverObracun['boltNeto'];
					$totalPerOH = ($totalOnline != 0) ? round($totNeto / $totalOnline, 2) : 0;
					$totalPerH = ($totalActiv != 0) ? round($totNeto / $totalActiv, 2) : 0;
					$driverObracun['uberOnline'] = $uberOnline;
					$driverObracun['uberActiv'] = $uberActiv;
					$driverObracun['uberPerH'] = $uberPerH;
					$driverObracun['uberPerOH'] = $uberPerOH;
					$driverObracun['boltOnline'] = $boltOnline;
					$driverObracun['boltActiv'] = $boltActiv;
					$driverObracun['boltPerH'] = $boltPerH;
					$driverObracun['boltPerOH'] = $boltPerOH;
					$driverObracun['totalPerH'] = $totalPerH;
					$driverObracun['totalPerOH'] = $totalPerOH;
//					print_r($driverObracun);
//					die();
					
				}
				
				
					//Provizija
				$drivProvizija = $this->izracunajProviziju($vozac);
				$vozac['ukupnoNeto'] += $driverObracun['myPosNeto'];
 				$driverObracun['provizija'] = $drivProvizija;
				$firmaObracun['provizija'] += $drivProvizija;
				$driverObracun['zaIsplatu'] = $driverObracun['ukupnoRazlika'] - $driverObracun['provizija'] + $driverObracun['boltNaljepnice'] - $driverObracun['najamVozila'];
				
					//spremi podatke za fiskalizaciju
				$fiskalizacija = array(
					'vozac_id' => $dr['id'],
					'vozac' => $dr['vozac'],
					'fleet' => $fleet,
					'month' => $fiskMonthNo,
					'bolt_fiskalizacija' => $boltFiskalizacija,
					'uber_fiskalizacija' => $uberFiskalizacija
				);
				$fiskalizacijaModel->insert($fiskalizacija);
				
				$driverObracun['fiskalizacijaUber'] = 0;
				$driverObracun['fiskalizacijaBolt'] = 0;
				// Provjeri dali treba naplatit fiskalizaciju
				if($wEMonth != $wSMonth){
					$brojFiskUber = $fiskalizacijaModel->where('vozac_id', $dr['id'])->where('month', $wSMonth)->where('uber_fiskalizacija', 1)->countAllResults();					
					$brojFiskBolt = $fiskalizacijaModel->where('vozac_id', $dr['id'])->where('month', $wSMonth)->where('bolt_fiskalizacija', 1)->countAllResults();
					
					if($brojFiskUber > 0){
						$driverObracun['fiskalizacijaUber'] = $postavkeFlote['fiskalizacija_uber'] ;
						$driverObracun['zaIsplatu'] -= $driverObracun['fiskalizacijaUber'];
						$firmaObracun['fiskalizacijaUber'] += $postavkeFlote['fiskalizacija_uber'] ;
					}
					if($brojFiskBolt > 0){
						$driverObracun['fiskalizacijaBolt'] = $postavkeFlote['fiskalizacija_bolt'] ;
						$driverObracun['zaIsplatu'] -= $driverObracun['fiskalizacijaBolt'];
						$firmaObracun['fiskalizacijaBolt'] += $postavkeFlote['fiskalizacija_bolt'] ;
					}
				}
				
				// Provjeri i automatski naplati Taximetar
				$driverObracun['taximetar'] = 0;
				if($dr['taximetar'] != 0){
					if($postavkeFlote['taximetar_tjedno'] != 0){
						$taximetar = $postavkeFlote['taximetar'] / 4 - $dr['popust_na_taximetar'];
						$driverObracun['taximetar'] = $taximetar;
						$firmaObracun['taximetar'] += $taximetar;
					}
				}
				
				// Doprinosi izračun
				$vozac['broj_sati'] = (float) $dr['broj_sati'];
				$driverObracun['doprinosi'] = 0;
				if($dr['prijava'] != 0){

					// Provjera dali je peti tjedan
					if($petiTjedan != true){
						$dopPlaca = $this->izracunajDoprinose($vozac);
						$driverObracun['doprinosi'] = $dopPlaca['doprinosi'];
						$firmaObracun['doprinosi'] += $dopPlaca['doprinosi'];
						$driverObracun['cetvrtinaNetoPlace'] = $dopPlaca['cetvrtinaNetoPlace'];
						$driverObracun['zaIsplatu'] -= $driverObracun['cetvrtinaNetoPlace'];
						$driverObracun['zaIsplatu'] -= $driverObracun['doprinosi'];
						$firmaObracun['netoPlaca'] += $dopPlaca['cetvrtinaNetoPlace'];
					}else{
						// Ako je peti tjedan i vozač ne radi kod nas puni mjesec
						if($vozac['daniPrijave'] < 31 ){
							$dopPlaca = $this->izracunajDoprinose($vozac);
							$driverObracun['doprinosi'] = $dopPlaca['doprinosi'];
							$firmaObracun['doprinosi'] += $dopPlaca['doprinosi'];
							$driverObracun['cetvrtinaNetoPlace'] = $dopPlaca['cetvrtinaNetoPlace'];
							$driverObracun['zaIsplatu'] -= $driverObracun['cetvrtinaNetoPlace'];
							$driverObracun['zaIsplatu'] -= $driverObracun['doprinosi'];
							$firmaObracun['netoPlaca'] += $dopPlaca['cetvrtinaNetoPlace'];
						}else{
							// Ako je peti tjedan i vozač radi kod nas puni mjesec
							$driverObracun['doprinosi'] = 0;
							$driverObracun['cetvrtinaNetoPlace'] = 0;
						}
						
					}
				}
				else{
					$driverObracun['doprinosi'] = 0;
					$driverObracun['cetvrtinaNetoPlace'] = 0;
				}
				// Nagrada preporucitelju
				$referal = array();
				if($dr['vrsta_nagrade'] != 'tjedno'){
					$referal['vozac_id'] = $dr['id'];
					$referal['vozac'] = $dr['vozac'];
					$referal['refered_by'] = $dr['refered_by'];
					$referal['referal_reward'] = 0;
					$referal['week'] = $week;
					$referal['fleet'] = $fleet;
				}else{
					if($dr['referal_reward'] != 0){
						if($driverObracun['provizija'] != 0){
							if($dr['vrsta_provizije'] != 'Postotak'){
								$daniRada = $vozac['daniRada'];
								if($daniRada > 7){$referal['referal_reward'] = $dr['referal_reward'];}
								elseif( $daniRada > 0){
									$reRew = $dr['referal_reward'] / 7 * $daniRada ;
									$referal['referal_reward'] = round($reRew, 2) ;
								}
								$referal['vozac_id'] = $dr['id'];
								$referal['vozac'] = $dr['vozac'];
								$referal['refered_by'] = $dr['refered_by'];
								$referal['week'] = $week;
								$referal['fleet'] = $fleet;
								}
							else{
								$postotak = $dr['referal_reward'];
								$refRew =  $postotak /10 * $driverObracun['provizija'];
								$referal['referal_reward'] = round($refRew, 2);
								$referal['vozac_id'] = $dr['id'];
								$referal['vozac'] = $dr['vozac'];
								$referal['refered_by'] = $dr['refered_by'];
								$referal['week'] = $week;
								$referal['fleet'] = $fleet;
							}
						}
						else{
							$referal['vozac_id'] = $dr['id'];
							$referal['vozac'] = $dr['vozac'];
							$referal['refered_by'] = $dr['refered_by'];
							$referal['referal_reward'] = 0;
							$referal['week'] = $week;
							$referal['fleet'] = $fleet;
						}

					}
				}
				if(!empty($referal)){
					$referalBonusModel = new ReferalBonusModel();
					$count = $referalBonusModel->where('vozac', $vozac['vozac'])->where('week', $week)->countAllResults();
					if($count == 0){
						$referalBonusModel->insert($referal);
					}
				}
				
				$obracunNaProviziju[] = $driverObracun;
			}
			// Svakome dodaj nagradu za preporuku
			foreach($obracunNaProviziju as $obracun){
				
				$referalNagrade = $this->referalReward($obracun);
				$obracun['referals'] = $referalNagrade['referals'];
				$obracun['refBonus'] = $referalNagrade['bonus'];
				$obracun['zaIsplatu'] +=  $obracun['refBonus'];
				$firmaObracun['refBonus'] +=$referalNagrade['bonus'];
				$obracunNaProviziju1[] = $obracun;
			} 
			$firmaObracun['zaIsplatu'] = $firmaObracun['ukupnoRazlikaSvi'] - $firmaObracun['provizija'] - $firmaObracun['taximetar'] - $firmaObracun['doprinosi'] - $firmaObracun['netoPlaca'] + $firmaObracun['refBonus'] - $firmaObracun['fiskalizacijaUber'] - $firmaObracun['fiskalizacijaBolt'];
			$firmaObracun['zaradaFirme'] = $firmaObracun['provizija'] - $firmaObracun['refBonus'];
			$firmaObracun['naplaceniTroskovi'] = $firmaObracun['taximetar'] + $firmaObracun['doprinosi'] + $firmaObracun['netoPlaca'] + $firmaObracun['fiskalizacijaUber'] + $firmaObracun['fiskalizacijaBolt'];
			$firmaObracun['firmaFiskalizacija'] = $firmaObracun['fiskalizacijaUber'] + $firmaObracun['fiskalizacijaBolt'];
			$firmaObracun['trebaOstatFirmi'] = $firmaObracun['zaradaFirme'] + $firmaObracun['naplaceniTroskovi'];
		}
		
		//
		
		if($koristi_activity != 0){
			$firmaObracun['activity'] = 1;
		}
		
		// Ako ima vozaca na placu
		if(!empty($naPlacu)){
			$data['naPlacu'] = $naPlacu;
		}
		$obracunFirmaModel = new ObracunFirmaModel();
		$weekSstari = $obracunFirmaModel->selectMax('week')->where('fleet', $fleet)->get()->getRowArray();
//		var_dump($weekSstari);
//		die();
		$weekStari = $weekSstari['week'] ;
		$data = $session->get();
		$dugoviModel = new DugoviModel();
		$dugovi = $dugoviModel->where('fleet', $fleet)->where('week', $weekStari) ->where('placeno', FALSE)->get()->getResultArray();
		
		$data['dugovi'] = $dugovi;
		$data['st_date'] = $razlikaDana['st_date'];
		$data['en_date'] = $razlikaDana['en_date'];
		$data['obracunNaProviziju'] = $obracunNaProviziju1;
		$data['postavkeFlote'] = $postavkeFlote;
		$data['firmaObracun'] = $firmaObracun;
		$data['page'] = 'Obracuni';
		$data['fleet'] = $fleet;
		return view('adminDashboard/header', $data)
		. view('adminDashboard/navBar')
		. view('adminDashboard/obracunaj')
			. view('footer');
		
	}
	
	public function izracunajActivity($bUId, $uUId, $week){
		
		// Extract year and week number
		$year = substr($week, 0, 4);
		$weekNumber = substr($week, 4);

		// Calculate start and end dates using DateTime
		$date = new \DateTime(); 
		$date->setISODate($year, $weekNumber); // Set to the first day (Monday) of the week

		$startDate = $date->format('Y-m-d'); // Get start date in YYYY-MM-DD format
		$date->modify('+6 days'); // Move to the end of the week (Sunday)
		$endDate = $date->format('Y-m-d');  // Get end date in YYYY-MM-DD format
		
		
		$boltActivityModel = new BoltDriverActivityModel();
		$uberActivityModel = new ActivityUberModel();

		// Fetch data from the database
		$uberActivity = $uberActivityModel->where('vozac', $uUId)
										  ->where('datum_unosa >=', $startDate) // Here might be the issue
										  ->where('datum_unosa <=', $endDate)   // And here as well
										  ->get()->getResultArray();

		$boltActivity = $boltActivityModel->where('driver_name', $bUId)
										  ->where('start_date >=', $startDate) // Same here
										  ->where('start_date <=', $endDate)   // Same here
										  ->get()->getResultArray();

		$uberOnline = 0;
		$uberActiv = 0;
		$boltOnline = 0;
		$boltActiv = 0;
		foreach($uberActivity as $ub){
			$uberOnline += $ub['vrijeme_na_mrezi'];
			$uberActiv += $ub['vrijeme_voznje'];
		}
		foreach($boltActivity as $ub){
			$boltOnline += $ub['online_hours'];
			$boltActiv += $ub['active_driving_hours'];
		}
		$data= array(
			'uberOnline' => $uberOnline,
			'uberActiv' => $uberActiv,
			'boltOnline' => $boltOnline,
			'boltActiv' => $boltActiv,
		);
		
		return($data);
	}
	
		public function referalReward($vozac){
			$refBon = 0;
			$referalBonusModel = new ReferalBonusModel();
			$referalBonusCount = $referalBonusModel->where('refered_by', $vozac['vozac'])->where('week', $vozac['week'])->countAllResults();
			if($referalBonusCount > 0){
				$referalBonus = $referalBonusModel->where('refered_by', $vozac['vozac'])->where('week', $vozac['week'])->get()->getResultArray();
				$refBonus = array();
				$ref_nagr = array();
				$refBon = 0;
				foreach($referalBonus as $ref){
					$ref_reward = $ref['referal_reward']; 
					$refBon += $ref_reward; 
					$refBonus['bonus'] = $refBon;
					$ref_nagr[] = array(
						'refered_vozac' => $ref['vozac'],
						'refered_nagrada' => $ref['referal_reward'],
					);
					$ref_nagr_json = json_encode($ref_nagr);
					$refBonus['referals'] = $ref_nagr_json;
				}
			}else{
				$refBonus['bonus'] = 0; 
				$refBonus['referals'] = 'nema';
			}

			
			return($refBonus);
		}
	
		public function izracunajDoprinose($vozac){
			$brojSati = $vozac['broj_sati'];
			$age = $vozac['age'];
			$doprinosiModel = new DoprinosiModel();
			$daniRada = $vozac['daniRada'];
			$daniPrijave = $vozac['daniPrijave'];
			$pocetakPrijave = $vozac['pocetak_prijave'];
			$doprinosi = 0;
			$cetvrtinaNetoPlace = 0;
			$mjesTrosak = $doprinosiModel
				->where('broj_sati >=', $brojSati - 0.3)
				->where('broj_sati <', $brojSati + 0.3) // Adjust this range as needed
				->get()
				->getRowArray();
			
			if($pocetakPrijave != '0000-00-00'){
				if($daniPrijave > 1){
					if($age < 30){
						$doprinosi = (float) $mjesTrosak['dop_do_30'] / 4;
						$doprinosi = round($doprinosi, 2);
						$brutoPlaca = (float) $mjesTrosak['bruto_do_30'] /4;
						$brutoPlaca = round($brutoPlaca, 2);
						$cetvrtinaNetoPlace = (float) $brutoPlaca - $doprinosi ;
					}
					else{
						$doprinosi = (float) $mjesTrosak['dop_od_30'] / 4;
						$doprinosi = round($doprinosi, 2);
						$brutoPlaca = (float) $mjesTrosak['bruto_od_30'] /4;
						$brutoPlaca = round($brutoPlaca, 2);
						$cetvrtinaNetoPlace = (float) $brutoPlaca - $doprinosi ;
					}
				}
			}
			
			$dopPlaca['doprinosi'] = $doprinosi;
			$dopPlaca['cetvrtinaNetoPlace'] = $cetvrtinaNetoPlace;
			
			return $dopPlaca;
		}
	
		public function izracunajProviziju($vozac){
			$session = session();
			$fleet = $session->get('fleet');
			$flotaModel = new FlotaModel();
			$postavkeFlote = $flotaModel->where('naziv', $fleet)->get()->getRowArray();
			$vrstaProvizije = $vozac['vrsta_provizije'];
			$iznosProvizije = (float) $vozac['iznos_provizije'];
			if($vozac['sezona'] != 1){
				$iznosFiksneProvizije = $postavkeFlote['provizija_fiks'];
			}else{
				$iznosFiksneProvizije = $postavkeFlote['provizija_fiks_sezona'];
			}
			
			$minimalnaProvizija = $postavkeFlote['koristi_min_proviziju'];
			$minimalnaProvizijaSezona = $postavkeFlote['koristi_min_proviziju_sezona'];
			$iznosMinimalneProvizije = $postavkeFlote['iznos_min_provizije'];
			$iznosMinimalneProvizijeSezona = $postavkeFlote['minimalna_provizija_sezona'];
			
			
			
			$popustNaProviziju = (float) $vozac['popust_na_proviziju'];
			$daniRada = $vozac['daniRada'];
			$ukupnoNeto = $vozac['ukupnoNeto'];
			$ukupnoNetoWithTaximetar = $ukupnoNeto + $vozac['taximetarNeto']; 
			$boltNaljepnice = $vozac['boltNaljepnice'];
			$naljepniceProvizija = $vozac['provizijaNaljepnice'];
			
			if($vrstaProvizije == 'Fiksna'){
				if($minimalnaProvizija != true){
					$provizija = $iznosFiksneProvizije - $popustNaProviziju;
					if($ukupnoNetoWithTaximetar > 0){
    					if($daniRada < 0){
    						$provizija = 0;
    					}elseif($daniRada >0 AND $daniRada < 7){
    						$provizija = $provizija / 7 * $daniRada; 
    						$provizija = round($provizija, 2);
    					}
					}
					else{
						$provizija = 0;
					}
				}else{
					if($ukupnoNetoWithTaximetar > 0){
						$provizija = $iznosFiksneProvizije - $popustNaProviziju;
						if($daniRada < 0){
							$provizija = 0;
						}elseif($daniRada >0 AND $daniRada < 7){
							$provizija = $provizija / 7 * $daniRada; 
							$provizija = round($provizija, 2);
						}
					}else{
						$provizija = $iznosFiksneProvizije - $popustNaProviziju;
						if($provizija > $iznosMinimalneProvizije){
							$provizija = $iznosMinimalneProvizije;
						}
					}
				}
								
			}elseif($vrstaProvizije == 'Postotak'){
				//Ako je postotak
				$popustnaMinimalnuproviziju = (float) $iznosMinimalneProvizije *  (float) $popustNaProviziju / 10;
				$iznosMinimalneProvizije = $iznosMinimalneProvizije - $popustnaMinimalnuproviziju;
				$iznosMinimalneProvizije = round($iznosMinimalneProvizije, 2);
				
				
				$iznosMinimalneProvizijeSezona = $iznosMinimalneProvizijeSezona - $popustnaMinimalnuproviziju;
				$iznosMinimalneProvizijeSezona = round($iznosMinimalneProvizijeSezona, 2);
			
			if($vozac['sezona'] != 1){
			
				if($minimalnaProvizija != true){
					$iznosProvizije = (float) $iznosProvizije - (float) $popustNaProviziju;
					$provizija = ($iznosProvizije / 100) * $ukupnoNetoWithTaximetar;
					$provizija = round($provizija, 2);
				}else{
					$iznosProvizije = (float) $iznosProvizije - (float) $popustNaProviziju;
					$provizija = ($iznosProvizije / 100) * $ukupnoNetoWithTaximetar;
					$provizija = round($provizija, 2);
					if($provizija < $iznosMinimalneProvizije){
						$provizija = $iznosMinimalneProvizije;
					}
				}				
				
			}else{
				if($minimalnaProvizija != true){
					$iznosProvizije = (float) $iznosProvizije - (float) $popustNaProviziju;
					$provizija = ($iznosProvizije / 100) * $ukupnoNetoWithTaximetar;
					$provizija = round($provizija, 2);
				}else{
					$iznosProvizije = (float) $iznosProvizije - (float) $popustNaProviziju;
					$provizija = ($iznosProvizije / 100) * $ukupnoNetoWithTaximetar;
					$provizija = round($provizija, 2);
					if($provizija < $iznosMinimalneProvizijeSezona){
						$provizija = $iznosMinimalneProvizijeSezona;
					}
				}				
			}

				
			
			}
			
			if($naljepniceProvizija > 0){
				$provNaljepnice = $naljepniceProvizija / 100 * $boltNaljepnice;
				$provizija = $provizija + $provNaljepnice;
				$provizija = round($provizija, 2);
			}
			
			return($provizija);
			
		}
	
		public function daniRada($vozac){
			$pocetakRada = $vozac['pocetak_rada'];
			$week = $vozac['week'];
			$yearNo = substr($week, 0, 4);
			$weekNo = substr($week, 4, 2);
			$date=date_create();
			date_isodate_set($date,$yearNo,$weekNo);
			$monthNo = date_format($date,"m");
			$fiskMonthNo = date_format($date,"Ym");
			$dob = $vozac['dob'];
			$pocetakPrijave = $vozac['pocetak_prijave'];

			$dateTime = new \DateTime();
			$dateTime->setISODate($yearNo, $weekNo);
			$result['start_date'] = $dateTime->format('Ymd');
			$result['st_date'] = $dateTime->format('Y-m-d');
			$result['weekStartMonth'] = $dateTime->format('Ym');
			$dateTime->modify('+7 days');
			$result['end_date'] = $dateTime->format('Ymd');
			$result['en_date'] = $dateTime->format('Y-m-d');
			$result['weekEndMonth'] = $dateTime->format('Ym');
			$start_date = $result['start_date'];
			$st_date = $result['st_date'];
			$en_date = $result['en_date'];
			$end_date = $result['end_date'];
			$wSMonth = $result['weekStartMonth'];
			$wEMonth = $result['weekEndMonth'];
			$today = date("Y-m-d");
			$dob = str_replace('/', '-', $dob);
						
			
			$diffGodina = date_diff(date_create($today), date_create($dob));
			$diffDanaRada = date_diff(date_create($pocetakRada), date_create($en_date));
			$diffDanaPrijave = date_diff(date_create($pocetakPrijave), date_create($en_date));
			$age = $diffGodina->format('%Y');
			$daniRada = (float) $diffDanaRada->format("%R%a");
			$daniPrijave = (float) $diffDanaPrijave->format("%R%a");
			$razlika['pocetakRada'] = $pocetakRada;
			$razlika['st_date'] = $st_date;
			$razlika['en_date'] = $en_date;
			$razlika['age'] = $age;
			$razlika['dani_rada'] = $daniRada;
			$razlika['dani_prijave'] = $daniPrijave;
			$razlika['wSMonth'] = $wSMonth;
			$razlika['wEMonth'] = $wEMonth;
			
			return($razlika);
		}
	public function getTaximetarReport($vozac){
		$TaximetarReportModel = new TaximetarReportModel();
		$session = session();
		$fleet = $session->get('fleet');
		$taxiR = $TaximetarReportModel->where('Email_vozaca', $vozac['taximetar_unique_id'])->where('week', $vozac['week'])->where('fleet', $fleet)->get()->getRowArray();
		if($taxiR != null){
			$driverReport = array(
				'taximetarNeto' => $taxiR['Ukupni_promet'],
				'taximetarNapojnica' => 0,
				'taximetarPovrati' => 0,
				'taximetarGotovina' => 0,
			);
		}else{
			$driverReport = array(
				'taximetarNeto' => 0,
				'taximetarNapojnica' => 0,
				'taximetarPovrati' => 0,
				'taximetarGotovina' => 0,
			);

		}
		return($driverReport);
		
	}
	
		public function getMyPosReport($vozac){
		$MyPosReportModel = new MyPosReportModel();
		$session = session();
		$fleet = $session->get('fleet');
		$myPos = $MyPosReportModel->where('Terminal_name', $vozac['myPos_unique_id'])->where('report_for_week', $vozac['week'])->where('fleet', $fleet)->get()->getResultArray();

				$driverReport = array(
					'myPosNeto' => 0,
					'myPosNapojnica' => 0,
					'myPosPovrati' => 0,
					'myPosGotovina' => 0,
					'myPosTransakcije' => 0
				);
			if($myPos != null){
				$myPosNeto = 0;
				$myPosTransakcije = json_encode($myPos);
				foreach($myPos as $mp){
					$myPosNeto += $mp['Amount'];
				}
				$myPosGotovina = 0;
				$myPosRazlika = $myPosNeto;
				$driverReport = array(
					'myPosNeto' => $myPosNeto,
					'myPosNapojnica' => 0,
					'myPosPovrati' => 0,
					'myPosGotovina' => 0,
					'myPosTransakcije' => $myPosTransakcije
				);
			}
			return($driverReport);
		}
	
	public function getBoltReport($vozac){
		$session = session();
		$fleet = $session->get('fleet');
		$BoltReportModel = new BoltReportModel();
		$countResults = $BoltReportModel->where('Vozac', $vozac['bolt_unique_id'])->where('report_for_week', $vozac['week'])->where('fleet', $fleet)->countAllResults();
		if($countResults > 1){
			$driverReportBoltTest = $BoltReportModel->where('Vozac', $vozac['bolt_unique_id'])->where('report_for_week', $vozac['week'])->where('fleet', $fleet)->get()->getResultArray();
			$bolt = $BoltReportModel->where('Vozac', $vozac['bolt_unique_id'])->where('Telefonski_broj_vozaca', $vozac['tel_broj'])->where('report_for_week', $vozac['week'])->where('fleet', $fleet)->get()->getRowArray();
//			echo '<pre>';
//			print_r($vozac);
//			print_r($bolt);
//			die();
		}else{
			$bolt = $BoltReportModel->where('Vozac', $vozac['bolt_unique_id'])->where('report_for_week', $vozac['week'])->where('fleet', $fleet)->get()->getRowArray();
		}
		
		if(empty($bolt)){
			$driverReport = array(
				'boltNeto' => 0,
				'boltNapojnica' => 0,
				'boltPovrati' => 0,
				'boltGotovina' => 0,
				'boltNaljepnice' => 0,
			);
		}
		else{
			if($bolt != null){
				$bolt_bruto = (float) $bolt['Bruto_iznos'] - (float) $bolt['Napojnica'] - (float) $bolt['Nadoknade'] - (float) $bolt['Naknada_za_cestarinu'] -  (float)$bolt['Bonus'];
				$bolt_naknada = $bolt_bruto * 0.25;
				$bolt_naknada = round($bolt_naknada, 2);
				$boltNeto = (float) $bolt_bruto + (float) $bolt['Naknada_za_rezervaciju_placanje'] + (float) $bolt['Naknada_za_rezervaciju_odbitak'] - (float) $bolt_naknada + (float) $bolt['Nadoknade'] + (float) $bolt['Povrati_novca'];
				$boltNapojnica = (float) $bolt['Napojnica'];
				$boltPovrati =  (float) $bolt['Naknada_za_cestarinu'];
				$boltGotovina = 0 - (float) $bolt['Voznje_placene_gotovinom_prikupljena_gotovina'];
				$driverReportBolt = array(
					'boltNeto' => $boltNeto,
					'boltNapojnica' => $boltNapojnica,
					'boltPovrati' => $boltPovrati,
					'boltGotovina' => $boltGotovina,
					'boltNaljepnice' => $bolt['Bonus'],
				);
				
				return($driverReportBolt);
			}
		}
	}
	
	public function getUberReport($vozac){
		$driverReport = array();
		$uberReport = array();
		$session = session();
		$fleet = $session->get('fleet');
		$UberReportModel = new UberReportModel();
		
		$countResults = $UberReportModel->where('Vozac', $vozac['uber_unique_id'])->where('report_for_week', $vozac['week'])->where('fleet', $fleet)->countAllResults();
		if($countResults > 1){
			$uberReportTest = $UberReportModel->where('Vozac', $vozac['uber_unique_id'])->where('report_for_week', $vozac['week'])->where('fleet', $fleet)->get()->getResultArray();
			$uberReport = $UberReportModel->where('UUID_vozaca', $vozac['UUID_vozaca'])->where('report_for_week', $vozac['week'])->where('fleet', $fleet)->get()->getRowArray();
//			echo 'found multiple records';
//			echo '<pre>';
//			print_r($vozac);
//			print_r($uberReport);
//			die();
		}else{
			$uberReport = $UberReportModel->where('Vozac', $vozac['uber_unique_id'])->where('report_for_week', $vozac['week'])->where('fleet', $fleet)->get()->getRowArray();
		}
		if(empty($uberReport)){
			$driverReport = array(
				'uberNeto' => 0,
				'uberNapojnica' => 0,
				'uberPovrat' => 0,
				'uberGotovina' => 0,
				

			);
		}else{
			if(isset($uberReport['Ukupna_zarada_Napojnica'])){
				$uberNeto = (float)$uberReport['Ukupna_zarada'] - (float)$uberReport['Ukupna_zarada_Napojnica'];
			}
			else{
				$uberNeto = (float)$uberReport['Ukupna_zarada'];
			}

			$driverReport = array(
				'uberNeto' => $uberNeto,
				'uberNapojnica' => (float)$uberReport['Ukupna_zarada_Napojnica'],
				'uberPovrat' => (float)$uberReport['Povrati_i_troskovi'],
				'uberGotovina' => (float)$uberReport['Isplate_Naplaceni_iznos_u_gotovini'],
			);
		}
		
		return($driverReport);
		
	}
	
	public function getDrivers(){
		$session = session();
		$fleet = $session->get('fleet');
		
		$driverModel = new DriverModel();
		$drivers['naPlacu'] = $driverModel->where('fleet', $fleet)->where('nacin_rada', 'placa')->where('aktivan', '1')->get()->getResultArray();
		$drivers['naProviziju'] = $driverModel->where('fleet', $fleet)->where('nacin_rada', 'provizija')->where('aktivan', '1')->orderBy('vozac', 'ASC')->get()->getResultArray();
		
		return($drivers);
	}
	
	public function obracunSave(){
		$session = session();
		$fleet = $session->get('fleet');
		helper(['form']);
		$obracunModel = new ObracunModel();
		$obracunFirmaModel = new ObracunFirmaModel();
		$obracun = $this->request->getVar('obracun');
		$obracun = json_decode($obracun);
		$obracunFirma = $this->request->getVar('obracunFirma');
		$obracunFirma = json_decode($obracunFirma);
		
		$obracunModel->insertBatch($obracun);
		
		$obracunFirmaModel->insert($obracunFirma);
		return redirect()->to('obracun');
		
	}
	
	public function sveSlikeSpremljene(){
		$obracunFirmaModel = new ObracunFirmaModel();
		$sveSlikeSpremljene = $this->request->getPost('sveSlikeSpremljene');
		$obracunFirmaID = $this->request->getPost('obracunFirmaID');
		
		$obracunFirmaModel->set('sveSlikeSpremljene', $sveSlikeSpremljene)
						->where('id', $obracunFirmaID)
						->update();
		return 'success';
	}
	
public function skinutiSlike() {
    $obracunModel = new ObracunModel();
    $obracunFirmaID = $this->request->getPost('obracunFirmaID');
    $fleet = $this->request->getPost('fleet');
    $week = $this->request->getPost('week');

    $obracuni = $obracunModel->where('fleet', $fleet)->where('week', $week)->get()->getResultArray();
    $zip = new \ZipArchive();

    $zipFilename = $week . '_obracun' . '_' . $fleet . '.zip';

    if ($zip->open($zipFilename, \ZipArchive::CREATE) === true) {
        $count = 0;

       foreach ($obracuni as $obracun) {
			$relativePath = $obracun['slikaObracuna'];
			$trimmedPath = substr($relativePath, 3);
		   $absolutePath = realpath(FCPATH . $relativePath);
			$fileLocation1 =  base_url($obracun['slikaObracuna']);
            $fileLocation = FCPATH . $trimmedPath;
            if (file_exists($fileLocation)) {
              $fileInZip = basename($fileLocation); // Extract just the filename
                $zip->addFile($fileLocation, $fileInZip); // Add files directly without specifying a folder
                $count += 1;
            } else {
                echo 'File does not exist: ' . $fileLocation . '</br>';
            }
        }

        $zip->close();

        // Set headers to force download
        header('Content-Type: application/zip');
        header("Content-Disposition: attachment; filename=$zipFilename");
        header("Content-Length: " . filesize($zipFilename));
        header("Pragma: no-cache");
        header("Expires: 0");

        // Send the zip file
        readfile($zipFilename);

        // Exit to prevent any further processing
        exit;
    } else {
        // Handle error: Unable to create the zip archive
        echo "Error creating the zip archive.";
    }
}

	public function obracunView($id = null){
    $obracunModel = new ObracunModel();
		$obracun = $obracunModel->getObracunById($id);
		return $obracun;
	}


//	public function saveScreenshots() {
//		
//		$obracunModel = new ObracunModel();
//		$directoryPath = '../public/obracuni'; // Adjust this to your actual directory path
//		$obracunId = $this->request->getPost('elementId');
//		$uploadedFile = $this->request->getFile('screenshot');
//
//		if ($uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
//			
//			$obracun = $obracunModel->where('id', $obracunId)->get()->getRowArray();
//			$vozac = $obracun['vozac'];
//			$raspon = $obracun['raspon'];
//			$newName = $vozac . '_' . $raspon . '.' . $uploadedFile->getExtension();
//			$uploadedFile->move($directoryPath, $newName);
//			
//			$directoryPath = '../obracuni';
//
//			$filePath = $directoryPath . '/' . $newName;
//			
//			$obracunModel->set('slikaObracuna', $filePath)
//						->where('id', $obracunId)
//						->update();
//			// Now, you have the complete file path, and you can save it to your database or perform other actions as needed.
//			
//		  	return $this->response->setJSON(['message' => 'Screenshot uploaded successfully']);
//		}else {
//			// Handle the case when the file upload is not valid
//			return $this->response->setJSON(['message' => 'Screenshot upload failed']);
//		}
//	}

	public function saveScreenshots() {
    $obracunModel = new ObracunModel();
    $directoryPath = '../public/obracuni'; // Adjust this to your actual directory path
    $directoryPath1 = '../obracuni'; // Adjust this to your actual directory path
    $obracunId = $this->request->getPost('elementId');
    $uploadedFile = $this->request->getFile('screenshot');

    if ($uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
        $obracun = $obracunModel->where('id', $obracunId)->get()->getRowArray();
        $vozac = $obracun['vozac'];
        $raspon = $obracun['raspon'];
        $newName = $vozac . '_' . $raspon . '.' . $uploadedFile->getExtension();

        // Check if the file already exists
        $existingFilePath = $directoryPath . '/' . $newName;
        if (file_exists($existingFilePath)) {
            // If the file exists, remove it
            unlink($existingFilePath);
			$newName = '1_' .$newName;
        }

        // Move the new file to the directory
        $uploadedFile->move($directoryPath, $newName);

        // Update the database with the new file path
        $filePath = $directoryPath1 . '/' . $newName;
        $obracunModel->set('slikaObracuna', $filePath)
            ->where('id', $obracunId)
            ->update();

        // Return success message
        return $this->response->setJSON(['message' => 'Screenshot uploaded successfully']);
    } else {
        // Handle the case when the file upload is not valid
        return $this->response->setJSON(['message' => 'Screenshot upload failed']);
    }
}
	
	
	
	public function tablica($week = null){
		
		$session = session();
		$fleet = $session->get('fleet');
		$obracunModel = new ObracunModel();
		$obracunFirmaModel = new ObracunFirmaModel();
		
		$vozaciObracun = $obracunModel->where('week', $week)->where('fleet', $fleet)->get()->getResultArray();		
		
		$data['page'] = 'Tablica';
		$data['table'] = $vozaciObracun;
		
	}
	
	public function obracunVozac($id){
		$obracunModel = new ObracunModel();
		$obracun = $obracunModel->where('id', $id)->get()->getRowArray();
		$page = $obracun['vozac'] .'obračun za period' .$obracun['raspon'];
		$data['page'] = $page;
		$data['driver'] = $obracun;
		return view('adminDashboard/header', $data)
		. view('adminDashboard/obracunVozac')
			. view('footer');
	}
	
	public function obracun($week = null){
		$session = session();
		$data = $session->get();
		$fleet = $session->get('fleet');
		$obracunModel = new ObracunModel();
		$obracunFirmaModel = new ObracunFirmaModel();
		
		$vozaciObracun = $obracunModel->where('week', $week)->where('fleet', $fleet)->get()->getResultArray();
		$firmaObracun = $obracunFirmaModel->where('week', $week)->where('fleet', $fleet)->get()->getRowArray();
		$isplataRevolut = $obracunModel->where('week', $week)->where('fleet', $fleet)->where('isplata', 'Revolut')->get()->getResultArray();
		
		
		$data['page'] = 'Obračun';
		$data['isplataRevolut'] = $isplataRevolut;
		$data['vozaciObracun'] = $vozaciObracun;
		$data['firmaObracun']  = $firmaObracun;
		
		return view('adminDashboard/header', $data)
		. view('adminDashboard/navBar')
		. view('adminDashboard/obracun1',$data)
			. view('footer');
	}
	public function obracunSavedodatni(){
		$dugoviModel = new DugoviModel();
		$session = session();
		$fleet = $session->get('fleet');
		helper(['form']);
		$obracun = array();
		$obracunModel = new ObracunModel();
		$obracunFirmaModel = new ObracunFirmaModel();
		$obracun = $_POST['obracun'];
		$obracunFirma = $_POST['obracunFirma'];
		$obracunFirma = json_decode($obracunFirma);
//		echo '<pre>';
//		var_dump($obracun);
//		die();

		foreach($obracun as $obr){
			
			$obr['zaIsplatu'] = floatval($obr['zaIsplatu'])  + floatval($obr['dug']) - floatval($obr['taximetar']);
			$obracunModel->insert($obr);
			$driver = array();
			if($obr['zaIsplatu'] < 0){
				$driver= array(
					'vozac_id' => $obr['vozac_id'],
					'vozac' => $obr['vozac'],
					'fleet' => $obr['fleet'],
					'week' => $obr['week'],
					'iznos' => $obr['zaIsplatu']
				);
				$dugoviModel->insert($driver);
			}
		}
		
		$obracunFirmaModel->insert($obracunFirma);

		return redirect()->to('obracun');
	}
	
	 public function exportData(){ 
     // file name 
     $filename = 'users_'.date('Ymd').'.csv'; 
     header("Content-Description: File Transfer"); 
     header("Content-Disposition: attachment; filename=$filename"); 
     header("Content-Type: application/csv; ");

     // get data 
     $obracunModel = new ObracunModel();
     $usersData = $obracunModel->where('week', $week)->where('fleet', $fleet)->where('isplata', 'Revolut')->get()->getResultArray();

     // file creation 
     $file = fopen('php://output', 'w');

     $header = array("ID","Name","Email","City"); 
     fputcsv($file, $header);
     foreach ($usersData as $key=>$line){ 
        fputcsv($file,$line); 
     }
     fclose($file); 
     exit; 
   }
	
	public function editVozacObracun($id = null){
		$obracunModel = new ObracunModel();
		$session = session();
		$data = $session->get();
		$obracun = $obracunModel->where('id', $id)->get()->getRowArray();
		$session = session();
		$fleet = $session->get('fleet');
		helper(['form']);
		
		$data['obracun'] = $obracun;
		$data['page'] = 'Editiraj Obračun';
		$data['fleet'] = $fleet;
		return view('adminDashboard/header', $data)
		. view('adminDashboard/navBar')
		. view('adminDashboard/editObracun',$data)
			. view('footer');
		
		
	}
	
	public function obracunUpdate(){
		
		$data = $this->request->getVar();
		$id = $data['id'];
//		$referals = $data['referals'];
//		$referals = json_encode($referals);
//		$data['referals'] = $referals;
		
		$obracunModel = new ObracunModel();
		
		$obracunModel -> update($id, $data);
		return redirect()->to('editirajObracun/' .$id);
		
	}
	
	
	public function checkId($week = null){
		$session = session();
		$fleet = $session->get('fleet');
		$role = $session->get('role');
		$DriverModel = new DriverModel();
		$UberReportModel = new UberReportModel();
		$BoltReportModel = new BoltReportModel();
		$MyPosReportModel = new MyPosReportModel();
		$TaximetarReportModel = new TaximetarReportModel();
		
		// GET ALL FROM UBER REPORTS AND CHECK AGAINST ALL DRIVERS
		
		$uberReport = $UberReportModel->where('report_for_week', $week)->where('fleet', $fleet)->get()->getResultArray();
		$vozac = '';
		$ubercount = 0;
		$ubercountIspravni = 0;
		$ubercountNeispravni = 0;
		$uber = array();
		foreach($uberReport as $report){
			$ubercount += 1;
			$vozac = $report['Vozac'];
			$result = $DriverModel->select('vozac, aktivan')->where('fleet', $fleet)->where('uber_unique_id', $vozac)->get()->getRowArray();
			if(!empty($result)){
				if($result['aktivan'] != 0){
					$uber[] = array(
						'Vozac' => $vozac,
						'ispravan' => 'DA',
						'aktivan' => 'DA',
					);
					$ubercountIspravni += 1;
				}else{
					$uber[] = array(
						'Vozac' => $vozac,
						'ispravan' => 'DA',
						'aktivan' => 'NE',
						);
					$ubercountNeispravni += 1;
				}
			}else{
				$uber[] = array(
					'Vozac' => $vozac,
					'ispravan' => 'NE',
					'aktivan' => 'NE',
					);
					$ubercountNeispravni += 1;
			}
		}
		$uberCount= array(
			'ubercountNeispravni' => $ubercountNeispravni,
			'ubercountIspravni' => $ubercountIspravni,
			'ubercount' => $ubercount,
		);
		
		// GET ALL FROM BOLT REPORTS AND CHECK AGAINST ALL DRIVERS
		$boltReport = $BoltReportModel->where('report_for_week', $week)->where('fleet', $fleet)->get()->getResultArray();
		$vozac = '';
		$boltcount = 0;
		$boltcountIspravni = 0;
		$boltcountNeispravni = 0;
		$bolt = array();
		foreach($boltReport as $report){
			$boltcount += 1;
			$vozac = $report['Vozac'];
			$result = $DriverModel->select('vozac, aktivan')->where('fleet', $fleet)->where('bolt_unique_id', $vozac)->get()->getRowArray();
			if(!empty($result)){
				if($result['aktivan'] != 0){
					$bolt[] = array(
						'Vozac' => $vozac,
						'ispravan' => 'DA',
						'aktivan' => 'DA',
					);
					$boltcountIspravni += 1;
				}else{
					$bolt[] = array(
						'Vozac' => $vozac,
						'ispravan' => 'DA',
						'aktivan' => 'NE',
						);
					$boltcountNeispravni += 1;
				}
			}else{
				$bolt[] = array(
					'Vozac' => $vozac,
					'ispravan' => 'NE',
					'aktivan' => 'NE',
					);
					$boltcountNeispravni += 1;
			}
		}
		$boltCount= array(
			'boltcountNeispravni' => $boltcountNeispravni,
			'boltcountIspravni' => $boltcountIspravni,
			'boltcount' => $boltcount,
		);
				// GET ALL FROM MYPOS REPORTS AND CHECK AGAINST ALL DRIVERS
		$myPosReport = $MyPosReportModel->where('report_for_week', $week)->where('fleet', $fleet)->get()->getResultArray();
		$vozac = '';
		$myPoscount = 0;
		$myPoscountIspravni = 0;
		$myPoscountNeispravni = 0;
		$myPos = array();
		foreach($myPosReport as $report){
			$myPoscount += 1;
			$vozac = $report['Terminal_name'];
			$result = $DriverModel->select('vozac, aktivan')->where('fleet', $fleet)->where('myPos_unique_id', $vozac)->get()->getRowArray();
			if(!empty($result)){
				if($result['aktivan'] != 0){
					$myPos[] = array(
						'Vozac' => $vozac,
						'ispravan' => 'DA',
						'aktivan' => 'DA',
					);
					$myPoscountIspravni += 1;
				}else{
					$myPos[] = array(
						'Vozac' => $vozac,
						'ispravan' => 'DA',
						'aktivan' => 'NE',
						);
					$myPoscountNeispravni += 1;
				}
			}else{
				$myPos[] = array(
					'Vozac' => $vozac,
					'ispravan' => 'NE',
					'aktivan' => 'NE',
					);
					$myPoscountNeispravni += 1;
			}
		}
		$myPosCount= array(
			'myPoscountNeispravni' => $myPoscountNeispravni,
			'myPoscountIspravni' => $myPoscountIspravni,
			'myPoscount' => $myPoscount,
		);
		// GET ALL FROM TAXIMETAR REPORTS AND CHECK AGAINST ALL DRIVERS
		$taximetarReport = $TaximetarReportModel->where('week', $week)->where('fleet', $fleet)->get()->getResultArray();
		$vozac = '';
		$taximetarcount = 0;
		$taximetarcountIspravni = 0;
		$taximetarcountNeispravni = 0;
		$taximetar = array();
		foreach($taximetarReport as $report){
			$taximetarcount += 1;
			$vozac = $report['Ime_vozaca'];
			$uniqueID = $report['Email_vozaca'];
			$result = $DriverModel->select('vozac, aktivan')->where('fleet', $fleet)->where('taximetar_unique_id', $uniqueID)->get()->getRowArray();
			if(!empty($result)){
				if($result['aktivan'] != 0){
					$taximetar[] = array(
						'Vozac' => $vozac,
						'ispravan' => 'DA',
						'aktivan' => 'DA',
					);
					$taximetarcountIspravni += 1;
				}else{
					$taximetar[] = array(
						'Vozac' => $vozac,
						'ispravan' => 'DA',
						'aktivan' => 'NE',
						);
					$taximetarcountNeispravni += 1;
				}
			}else{
				$taximetar[] = array(
					'Vozac' => $vozac,
					'ispravan' => 'NE',
					'aktivan' => 'NE',
					);
					$taximetarcountNeispravni += 1;
			}
		}
		$taximetarCount= array(
			'taximetarcountNeispravni' => $taximetarcountNeispravni,
			'taximetarcountIspravni' => $taximetarcountIspravni,
			'taximetarcount' => $taximetarcount,
		);		
		
		$data['taximetar'] = $taximetar;
		$data['taximetarCount'] = $taximetarCount;
		$data['myPos'] = $myPos;
		$data['myPosCount'] = $myPosCount;
		$data['bolt'] = $bolt;
		$data['boltCount'] = $boltCount;
		$data['uber'] = $uber;
		$data['uberCount'] = $uberCount;
		$data['page'] = 'Provjeri unique ID';
		$data['fleet'] = $fleet;
		$data['role'] = $role;
		return view('adminDashboard/header', $data)
		. view('adminDashboard/navBar')
		. view('adminDashboard/provjeraUniqueID',$data)
			. view('footer');
		
		
	}
	
	
	
	
	
	
	
}