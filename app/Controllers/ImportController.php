<?php

namespace App\Controllers;
require_once FCPATH . '../vendor/autoload.php';
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use App\Models\UberReportModel;
use App\Models\BoltReportModel;
use App\Models\MyPosReportModel;
use App\Models\DriverModel;
use App\Models\FlotaModel;
use App\Models\TvrtkaModel;
use App\Models\TaximetarReportModel;

use Twilio\Rest\Client;
use App\Libraries\UltramsgLib;

class ImportController extends BaseController
{

    public function __construct()
    {
    }

	
public function taximetarImport() {
    $session = session();
    $fleet = $session->get('fleet');
    $data = $session->get();
	$session->set('previous_url', current_url());
    // Validate the uploaded file
	$input = $this->validate([
		'file' => 'uploaded[file]|max_size[file,10240]|ext_in[file,csv]|mime_in[file,text/csv]',
	]);
    if ($this->request->getMethod() === 'post') {
        // Get the uploaded file
        $file = $this->request->getFile('file');

        if ($file !== null) {
            if ($file->getError() === UPLOAD_ERR_OK) {
                // Check if the file has a valid extension and MIME type
                if ($file->getClientMimeType() === 'text/csv') {
                    // File upload was successful and it's an Excel file
					$originalFileName = $file->getName();
					$pattern = '/(\d{2})-(\d{2})-(\d{2})/';

					// Perform the regex match
					if (preg_match($pattern, $originalFileName, $matches)) {
						// Extract the matched date components
						$day = $matches[1];
						$day = $day - 2;
						$month = $matches[2];
						$year = '20' . $matches[3]; // Assuming it's 20YY format

						// Create a DateTime object
						$date = new \DateTime("$year-$month-$day");


						// Format the date as per your requirement
						$formatted_date = $date->format('Y-m-d'); // Or any other format you prefer
						$week = date("W", strtotime($formatted_date));
						$week = $year. $week;
					} else {
						echo "Date not found in the filename.";
					}
					// Generate a random name for the file
					$randomFileName = bin2hex(random_bytes(8)); // Generates a random hexadecimal string

					// Move the uploaded file to the desired location with the random name
					$file->move('../public/csvfile', $randomFileName);					
					$file = fopen("../public/csvfile/".$randomFileName,"r");
					
					$rows   = array_map('str_getcsv', file("../public/csvfile/".$randomFileName));
					$rows = array_slice($rows, 1);
					$header_row = array_shift($rows);
					//Get the first row that is the HEADER row.
					$header_row = str_replace(": ", "", $header_row);
					$header_row = str_replace("č", "c", $header_row);
					$header_row = str_replace("ć", "c", $header_row);
					$header_row = str_replace("š", "s", $header_row);
					$header_row = str_replace("ž", "z", $header_row);
					$header_row = str_replace("đ", "d", $header_row);
					$header_row = str_replace(" ", "_", $header_row);
					$header_row = str_replace(":", "_", $header_row);
					//This array holds the final response.
					$taximetarReport    = array();
					$countReport = 0;
					foreach($rows as $row) {
						if(!empty($row)){
							$taximetarReport[] = array_combine($header_row, $row);
							$countReport++;
						}
					}
					foreach($taximetarReport as &$report){
						$report['fleet'] = $fleet;
						$report['week'] = $week;
					}
	                    echo 'File uploaded successfully </br> <pre>';
					$taximetarReport = array_slice($taximetarReport, 1);
					
					foreach($taximetarReport as &$report){
						$taximetarReport1[]= array(
							'Br' 			=> $report['Br.'],
							'Ime_vozaca' 	=> $report['Ime_vozaca'],
							'Email_vozaca'	=> $report['Email_vozaca'],
							'Tel_broj' 		=> $report['Tel._broj'],
							'Ukupni_promet' => $report['Ukupni_promet_(€)'],
							'Ukupni_promet' => str_replace(",", "", $report['Ukupni_promet_(€)']),
							'fleet' 		=> $report['fleet'],
							'week' 			=> $report['week'],
						);
					}
				
				$TaximetarReportModel = new TaximetarReportModel();
				$findRecord = $TaximetarReportModel->where('week', $week)->where('fleet', $fleet)->countAllResults();
					
				$count1 = 0;
				if($findRecord == 0){
					if($TaximetarReportModel->insertBatch($taximetarReport1)){
						$errorMessage = $countReport .' vozača je unikatno i spremljeno u bazu podataka';
						$session->setFlashdata('messageTaximetar', $errorMessage);
						session()->setFlashdata('alert-class', 'alert-success');
					   return redirect()->to("/index.php/uberImport");
					}else{
						$errorMessage = $countReport .' vozača je unikatno ali je došlo do nepoznate pogreške prilikom spremanja u bazu podataka. Kontaktirati administratora';
						$session->setFlashdata('messageTaximetar', $errorMessage);
						session()->setFlashdata('alert-class', 'alert-danger');
					   return redirect()->to("/index.php/uberImport");
					}
				}else{
					$updateCount = 0;
					$insertCount = 0;
					foreach($taximetarReport1 as $report){
						$existingRecord = $TaximetarReportModel->where('week', $report['week'])
													 ->where('fleet', $report['fleet'])
													 ->where('Email_vozaca', $report['Email_vozaca'])
													 ->first();

						if ($existingRecord) {
							// Data exists in the database
							if ($existingRecord['Ukupni_promet'] != $report['Ukupni_promet']) {
								// Update Ukupni_promet if different
								$TaximetarReportModel->update($existingRecord['id'], ['Ukupni_promet' => $report['Ukupni_promet']]);
								$updateCount++;
							}
							// Skip if data matches
							continue;
						}else{
							$TaximetarReportModel->insert($report);
							$insertCount++;
						}						
					}
					$errorMessage = $countReport .' vozača je na popisu od toga ' .$updateCount .' već postoji u bazi, ali su podaci ažurirani i ' .$insertCount .' nije postojalo u bazi pa su spremljeni';
					$session->setFlashdata('messageTaximetar', $errorMessage);
					session()->setFlashdata('alert-class', 'alert-success');
				   return redirect()->to("/index.php/uberImport");
				}

					
               } else {
                    // Invalid file type
                    $errorMessage = 'Invalid file type. Only .csv files are allowed.';
                    $session->setFlashdata('messageTaximetar', $errorMessage);
                    session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("/index.php/uberImport");
                }
            } else {
                // Handle the upload error
                switch ($file->getError()) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $errorMessage = 'The uploaded file exceeds the maximum file size limit.';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errorMessage = 'The uploaded file was only partially uploaded.';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $errorMessage = 'No file was uploaded.';
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $errorMessage = 'Missing a temporary folder. Upload failed.';
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $errorMessage = 'Failed to write file to disk. Upload failed.';
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $errorMessage = 'A PHP extension stopped the file upload. Upload failed.';
                        break;
                    default:
                        $errorMessage = 'Unknown upload error. Upload failed.';
                        break;
                }
                $session->setFlashdata('messageTaximetar', $errorMessage);
                session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("/index.php/uberImport");
            }
        } else {
				$errorMessage = 'No file posted';
                $session->setFlashdata('messageTaximetar', $errorMessage);
                session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("/index.php/uberImport");
        }
    }
}
	
	
	
	
public function boltImport() {
    $session = session();
    $fleet = $session->get('fleet');
    $data = $session->get();
	$session->set('previous_url', current_url());
    // Validate the uploaded file
	$input = $this->validate([
		'file' => 'uploaded[file]|max_size[file,10240]|ext_in[file,csv]|mime_in[file,text/csv]',
	]);
    if ($this->request->getMethod() === 'post') {
        // Get the uploaded file
        $file = $this->request->getFile('file');

        if ($file !== null) {
            if ($file->getError() === UPLOAD_ERR_OK) {
                // Check if the file has a valid extension and MIME type
                if ($file->getClientMimeType() === 'text/csv') {
                    // File upload was successful and it's an Excel file
					$originalFileName = $file->getName();
					$pattern = '/(\d{2})_(\d{2})_(\d{4})/';

					// Perform the regex match
					if (preg_match($pattern, $originalFileName, $matches)) {
						// Extract the matched date components
						$day = $matches[1];
						$day = $day;
						$month = $matches[2];
						$year = $matches[3]; // Assuming it's 20YY format

						// Create a DateTime object
						$date = new \DateTime("$year-$month-$day");


						// Format the date as per your requirement
						$formatted_date = $date->format('Y-m-d'); // Or any other format you prefer
						$week = date("W", strtotime($formatted_date));
						$week = $year. $week;
					} else {
						echo "Date not found in the filename.";
					}
					// Generate a random name for the file
					$randomFileName = bin2hex(random_bytes(8)); // Generates a random hexadecimal string

					// Move the uploaded file to the desired location with the random name
					$file->move('../public/csvfile', $randomFileName);					
					$file = fopen("../public/csvfile/".$randomFileName,"r");
					
					$rows   = array_map('str_getcsv', file("../public/csvfile/".$randomFileName));
					//$rows = array_slice($rows, 1);
					$header_row = array_shift($rows);
					//Get the first row that is the HEADER row.
					$header_row = str_replace(['"', '﻿', '<pre>'], '', $header_row);
					$header_row = str_replace(": ", "", $header_row);
					$header_row = str_replace("'", "", $header_row);
					$header_row = str_replace("|", "", $header_row);
					$header_row = str_replace("- ", "", $header_row);
					$header_row = str_replace("€", "", $header_row);
					$header_row = str_replace("č", "c", $header_row);
					$header_row = str_replace("ć", "c", $header_row);
					$header_row = str_replace("š", "s", $header_row);
					$header_row = str_replace("ž", "z", $header_row);
					$header_row = str_replace("đ", "d", $header_row);
					$header_row = str_replace(" ", "_", $header_row);
					$header_row = str_replace(":", "_", $header_row);
					$header_row = preg_replace('/[[:cntrl:]]/', '', $header_row);
					//This array holds the final response.
					$taximetarReport    = array();
					$countReport = 0;
					foreach($rows as $row) {
						if(!empty($row)){
							$taximetarReport[] = array_combine($header_row, $row);
							$countReport++;
						}
					}
					foreach($taximetarReport as &$report){
						$report['fleet'] = $fleet;
						$report['week'] = $week;
						$taximetarReport1[]= array(
							'Vozac' 			=> $report['Vozac'],
							'Bruto_iznos' 	=> $report['Ukupan_prihod'],
							'Otkazna_naknada'	=> $report['Otkazne_naknade'],
							'Naknada_za_rezervaciju_placanje' 		=> $report['Naknade_za_rezervaciju'],
							'Naknada_za_cestarinu' => $report['Cestarine'],
							'Voznje_placene_gotovinom_prikupljena_gotovina' 		=> $report['Gotovina'],
							'Bonus' 		=> $report['Bonusi'],
							'Nadoknade' 		=> $report['Nadoknade'],
							'Napojnica' 		=> $report['Napojnice'],
							'fleet' 		=> $report['fleet'],
							'report_for_week' 			=> $report['week'],
						);
					}
					//$taximetarReport = array_slice($taximetarReport, 1);
					

//					echo '<pre>';
//					var_dump($taximetarReport1);
//					die();
				
				$TaximetarReportModel = new BoltReportModel();
				$findRecord = $TaximetarReportModel->where('report_for_week', $week)->where('fleet', $fleet)->countAllResults();
					
				$count1 = 0;
				if($findRecord == 0){
					if($TaximetarReportModel->insertBatch($taximetarReport1)){
						$errorMessage = $countReport .' vozača je unikatno i spremljeno u bazu podataka';
						$session->setFlashdata('messageBolt', $errorMessage);
						session()->setFlashdata('alert-class', 'alert-success');
					   return redirect()->to("/index.php/uberImport");
					}else{
						$errorMessage = $countReport .' vozača je unikatno ali je došlo do nepoznate pogreške prilikom spremanja u bazu podataka. Kontaktirati administratora';
						$session->setFlashdata('messageBolt', $errorMessage);
						session()->setFlashdata('alert-class', 'alert-danger');
					   return redirect()->to("/index.php/uberImport");
					}
				}else{
					$updateCount = 0;
					$insertCount = 0;
					foreach($taximetarReport1 as $report){
						$existingRecord = $TaximetarReportModel->where('week', $report['week'])
													 ->where('fleet', $report['fleet'])
													 ->where('Email_vozaca', $report['Email_vozaca'])
													 ->first();

						if ($existingRecord) {
							// Data exists in the database
							if ($existingRecord['Ukupni_promet'] != $report['Ukupni_promet']) {
								// Update Ukupni_promet if different
								$TaximetarReportModel->update($existingRecord['id'], ['Ukupni_promet' => $report['Ukupni_promet']]);
								$updateCount++;
							}
							// Skip if data matches
							continue;
						}else{
							$TaximetarReportModel->insert($report);
							$insertCount++;
						}						
					}
					$errorMessage = $countReport .' vozača je na popisu od toga ' .$updateCount .' već postoji u bazi, ali su podaci ažurirani i ' .$insertCount .' nije postojalo u bazi pa su spremljeni';
					$session->setFlashdata('messageBolt', $errorMessage);
					session()->setFlashdata('alert-class', 'alert-success');
				   return redirect()->to("/index.php/uberImport");
				}

					
               } else {
                    // Invalid file type
                    $errorMessage = 'Invalid file type. Only .csv files are allowed.';
                    $session->setFlashdata('messageBolt', $errorMessage);
                    session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("/index.php/uberImport");
                }
            } else {
                // Handle the upload error
                switch ($file->getError()) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $errorMessage = 'The uploaded file exceeds the maximum file size limit.';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $errorMessage = 'The uploaded file was only partially uploaded.';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $errorMessage = 'No file was uploaded.';
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $errorMessage = 'Missing a temporary folder. Upload failed.';
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $errorMessage = 'Failed to write file to disk. Upload failed.';
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $errorMessage = 'A PHP extension stopped the file upload. Upload failed.';
                        break;
                    default:
                        $errorMessage = 'Unknown upload error. Upload failed.';
                        break;
                }
                $session->setFlashdata('messageBolt', $errorMessage);
                session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("/index.php/uberImport");
            }
        } else {
				$errorMessage = 'No file posted';
                $session->setFlashdata('messageBolt', $errorMessage);
                session()->setFlashdata('alert-class', 'alert-danger');
                   return redirect()->to("/index.php/uberImport");
        }
    }
}










}