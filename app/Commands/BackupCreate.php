<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Libraries\BackupService;

class BackupCreate extends BaseCommand
{
    protected $group       = 'Backup';
    protected $name        = 'backup:create';
    protected $description = 'Creates a backup of the database.';

    public function run(array $params)
    {
        $type = $params[0] ?? 'manual'; // Default to manual if no type provided
        CLI::write("Creating {$type} backup...", 'yellow');

        $backupService = new BackupService();

        try {
            $filename = $backupService->createBackup($type);
            CLI::write("Backup created successfully: {$filename}", 'green');
        } catch (\Exception $e) {
            CLI::error("Backup failed: " . $e->getMessage());
        }
    }
}
