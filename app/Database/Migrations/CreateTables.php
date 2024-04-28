<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\Migration\Forge;

class CreateTables extends Migration
{
	protected $rowNames;

    public function __construct(array $rowNames)
    {
        $this->rowNames = $rowNames;
    }
  public function up()
    {
        $table = 'racuni';

        $this->forge->addField('id');
        foreach ($this->rowNames as $rowName) {
            $this->forge->addField($rowName, 'VARCHAR', 255); // Assuming all columns are of VARCHAR type with a length of 255
        }

        $this->forge->createTable($table);
    }

    public function down()
    {
        $table = 'racuni';

        $this->forge->dropTable($table);
    }
   
}