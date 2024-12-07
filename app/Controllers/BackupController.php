<?php

namespace App\Controllers;

use App\Libraries\BackupService;

class BackupController extends BaseController
{
    protected $backupService;

    public function __construct()
    {
        $this->backupService = new BackupService();
    }

    public function index()
    {
        $data['backups'] = $this->backupService->getBackups();
        return view('backups/index', $data);
    }

    public function createBackup($type)
    {
        $filename = $this->backupService->createBackup($type);
        if ($filename) {
            echo "Backup created successfully: {$filename}";
        } else {
            echo "Failed to create backup.";
        }
    }

    public function rotateBackups($type)
    {
        $retainCount = $this->getRetentionCount($type);
        $this->backupService->rotateBackups($type, $retainCount);
        echo "{$type} backups rotated.";
    }

    public function sendBackupLinks($type)
    {
        $emails = ['recipient@example.com'];
        if ($this->backupService->sendBackupLinks($emails, $type)) {
            echo "Backup links sent successfully.";
        } else {
            echo "Failed to send backup links.";
        }
    }
	
	    public function restore($filename)
    {
        try {
            $this->backupService->restoreBackup($filename);
            return redirect()->to('/backups')->with('message', 'Backup restored successfully!');
        } catch (\Exception $e) {
            return redirect()->to('/backups')->with('error', $e->getMessage());
        }
    }
	
public function download($filename)
{
    $filePath = WRITEPATH . 'backups/' . $filename;

    // Check if the file exists
    if (!file_exists($filePath)) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Backup file not found: {$filename}");
    }

    // Use the response object to serve the file
    return $this->response->download($filePath, null)->setFileName($filename);
}


	
	
	
	public function manualBackup()
{
    try {
        $filename = $this->backupService->createBackup('manual');
        if ($filename) {
            return redirect()->to('/backups')->with('message', "Manual backup created successfully: {$filename}");
        } else {
            return redirect()->to('/backups')->with('error', 'Failed to create manual backup.');
        }
    } catch (\Exception $e) {
        return redirect()->to('/backups')->with('error', $e->getMessage());
    }
}


    private function getRetentionCount($type)
    {
        switch ($type) {
            case 'hourly':
                return 24;
            case 'daily':
                return 7;
            case 'weekly':
                return 4;
            case 'monthly':
                return 12;
            default:
                return 0;
        }
    }
}
