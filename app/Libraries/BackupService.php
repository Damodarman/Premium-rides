<?php

namespace App\Libraries;

class BackupService
{
    protected $backupDir;
    protected $dbHost;
    protected $dbName;
    protected $dbUser;
    protected $dbPassword;

    public function __construct()
    {
        $this->backupDir = WRITEPATH . 'backups/';

        // Ensure the backup directory exists
        if (!is_dir($this->backupDir)) {
            mkdir($this->backupDir, 0755, true);
        }

        // Load database configuration
        $dbConfig = config('Database')->default;
        $this->dbHost = $dbConfig['hostname'];
        $this->dbName = $dbConfig['database'];
        $this->dbUser = $dbConfig['username'];
        $this->dbPassword = $dbConfig['password'];
    }

    /**
     * Create a database backup using mysqldump.
     */
    public function createBackup($type = 'manual')
    {
        $filename = "{$type}_backup_" . date('Y-m-d_H-i-s') . ".sql.gz";
        $filepath = $this->backupDir . $filename;

        // Command to create a backup
        $command = sprintf(
            'mysqldump -h %s -u %s -p%s %s | gzip > %s',
            escapeshellarg($this->dbHost),
            escapeshellarg($this->dbUser),
            escapeshellarg($this->dbPassword),
            escapeshellarg($this->dbName),
            escapeshellarg($filepath)
        );

        // Execute the command
        $output = [];
        $result = null;
        exec($command, $output, $result);

        // Check if the backup was created successfully
        if ($result === 0) {
            return $filename; // Success
        }

        return false; // Failure
    }

    /**
     * Get all backup files.
     */
    public function getBackups()
    {
        $files = glob($this->backupDir . "*.gz");
        $backups = [];

        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => filesize($file),
                'created_at' => filemtime($file),
                'download_link' => base_url('backups/' . basename($file)),
            ];
        }

        return $backups;
    }

    /**
     * Restore a backup.
     */
    public function restoreBackup($filename)
    {
        $filepath = $this->backupDir . $filename;

        // Check if the file exists
        if (!file_exists($filepath)) {
            throw new \Exception("Backup file not found: {$filename}");
        }

        // Command to restore the database
        $command = sprintf(
            'gunzip < %s | mysql -h %s -u %s -p%s %s',
            escapeshellarg($filepath),
            escapeshellarg($this->dbHost),
            escapeshellarg($this->dbUser),
            escapeshellarg($this->dbPassword),
            escapeshellarg($this->dbName)
        );

        // Execute the command
        $output = [];
        $result = null;
        exec($command, $output, $result);

        // Check if the restoration was successful
        if ($result === 0) {
            return true; // Success
        }

        return false; // Failure
    }
}
