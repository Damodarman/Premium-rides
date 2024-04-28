<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Config\Database;
use CodeIgniter\Database\Migration;
use Config\Services;
use App\Database\Migrations\CreateTables;
use CodeIgniter\Database\MigrationRunner;
use App\Models\RacuniModel;



class ExcelImportController extends Controller
{
	protected $db;
    protected $dbforge;
	protected $migrationRunner;

public function __construct()
    {
        helper('config');
    }
public function showImportForm()
    {
        return view('adminDashboard/importForm');
    }

public function importData(){
		if($file = $this->request->getFile('csv_file')) {
			if ($file->isValid() && ! $file->hasMoved()) {
				$racuniModel = new RacuniModel();
				
				$original_name = $file->getName();
				$newName = $file->getRandomName();
                $file->move('../public/csvfile', $newName);
                $file = fopen("../public/csvfile/".$newName,"r");
				$rows   = array_map('str_getcsv', file("../public/csvfile/".$newName));
				$header_row = array_shift($rows);
				$header_row = $racuniModel->get()->getFieldNames();
				array_shift($header_row);
				foreach($rows as $row) {
					if(!empty($row)){
						$racuni[] = array_combine($header_row, $row);
						
					}
				}
				
			foreach ($racuni as &$row) {
				if (!empty($row)) {
					$datum_dokumenta = \DateTime::createFromFormat('m/d/Y', $row['datum_dokumenta']);
					$formated_datum_dokumenta = $datum_dokumenta->format('Y-m-d');
					$row['datum_dokumenta'] = $formated_datum_dokumenta;

					$datum_racuna = \DateTime::createFromFormat('m/d/Y', $row['datum_računa']);
					$formated_datum_racuna = $datum_racuna->format('Y-m-d');
					$row['datum_računa'] = $formated_datum_racuna;

					$datum_dospijeca = \DateTime::createFromFormat('m/d/Y', $row['datum_dospijeća']);
					$formated_datum_dospijeca = $datum_dospijeca->format('Y-m-d');
					$row['datum_dospijeća'] = $formated_datum_dospijeca;

					$datum_kreiranja = \DateTime::createFromFormat('m/d/Y', $row['datum_kreiranja']);
					$formated_datum_kreiranja = $datum_kreiranja->format('Y-m-d');
					$row['datum_kreiranja'] = $formated_datum_kreiranja;
				}
			}			
			
				$racuniModel->insertBatch($racuni);
				
			

				echo 'Migration completed successfully.';
			}
			else{
				echo 'file is not valid or is not movec';
			}
		}
		else{
			echo 'file is not uploaded';
		}
	}

private function createTable($columnNames)
{
    // Set your desired table name
    $tableName = 'racuni';

    // Check if the table exists
    $tableExists = $this->db->tableExists($tableName);

    // Create the table if it doesn't exist
    if (!$tableExists) {
        // Create the table using the schema builder
        $fields = [];
        foreach ($columnNames as $columnName) {
            $fields[$columnName] = [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ];
        }

        $this->dbforge->addKey('id', true); // Assuming you have an 'id' column as the primary key, adjust if necessary
        $this->dbforge->addFields($fields);
        $this->dbforge->createTable($tableName);
    }
}

private function importToDatabase($worksheet, $columnNames)
{
    // Set your desired table name
    $tableName = 'racuni';

    // Iterate through the rows (excluding the header row) and insert the values into the table
    foreach ($worksheet->getRowIterator(2) as $row) {
        $rowData = [];
        foreach ($row->getCellIterator() as $cell) {
            $rowData[] = $cell->getValue();
        }
        $data = array_combine($columnNames, $rowData);

        // Insert the data into the table using CodeIgniter's Query Builder
        $this->db->table($tableName)->insert($data);
    }
}
}
