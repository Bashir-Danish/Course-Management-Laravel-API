<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class BackupController extends Controller
{
    public function index()
    {
        return view('backup.index');
    }

    public function create(Request $request)
    {
        try {
            $request->validate([
                'backup_name' => 'required|string|max:255'
            ]);

            // Sanitize filename
            $filename = preg_replace('/[^a-zA-Z0-9-_]/', '', $request->backup_name);
            $filename = $filename . '_' . date('Y-m-d_H-i-s') . '.sql';
            
            // Create backup directory if it doesn't exist
            $backupPath = storage_path('app/backups');
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $filePath = "{$backupPath}/{$filename}";

            // Get database configuration
            $database = config('database.connections.mysql.database');
            
            // For XAMPP with root user and no password
            $command = sprintf(
                'C:\\xampp\\mysql\\bin\\mysqldump.exe -u root %s > %s 2>&1',
                escapeshellarg($database),
                escapeshellarg($filePath)
            );

            // Execute command and capture output
            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);

            // Check for errors
            if ($returnVar !== 0) {
                $errorMessage = implode("\n", $output);
                throw new Exception("Database backup failed: " . $errorMessage);
            }

            if (!file_exists($filePath)) {
                throw new Exception('Backup file was not created');
            }

            if (filesize($filePath) === 0) {
                unlink($filePath);
                throw new Exception('Backup file is empty');
            }

            return response()->download($filePath)->deleteFileAfterSend(true);
            
        } catch (Exception $e) {
            // Clean up any failed backup file
            if (isset($filePath) && file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Log the error for debugging
            \Log::error('Backup failed: ' . $e->getMessage());
            \Log::error('Command output: ' . (isset($output) ? implode("\n", $output) : 'No output'));
            
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 