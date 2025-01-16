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

            $filename = preg_replace('/[^a-zA-Z0-9-_]/', '', $request->backup_name);
            $filename = $filename . '_' . date('Y-m-d_H-i-s') . '.sql';
            
            $backupPath = storage_path('app/backups');
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }
            
            $filePath = "{$backupPath}/{$filename}";

            $database = config('database.connections.mysql.database');
            
            $command = sprintf(
                'C:\\xampp\\mysql\\bin\\mysqldump.exe -u root %s > %s 2>&1',
                escapeshellarg($database),
                escapeshellarg($filePath)
            );

            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);

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
            if (isset($filePath) && file_exists($filePath)) {
                unlink($filePath);
            }
        
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 