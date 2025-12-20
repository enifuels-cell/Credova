<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Carbon\Carbon;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'backup:database {--compress} {--encrypt}';

    /**
     * The console command description.
     */
    protected $description = 'Create a backup of the database with optional compression and encryption';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting database backup...');

        try {
            $backupPath = $this->createBackup();
            
            if ($this->option('compress')) {
                $backupPath = $this->compressBackup($backupPath);
            }
            
            if ($this->option('encrypt')) {
                $backupPath = $this->encryptBackup($backupPath);
            }

            $this->cleanOldBackups();
            
            $this->info("Backup completed successfully: {$backupPath}");
            
            Log::info('Database backup completed', [
                'file' => $backupPath,
                'size' => $this->formatBytes(filesize($backupPath)),
                'compressed' => $this->option('compress'),
                'encrypted' => $this->option('encrypt')
            ]);

        } catch (\Exception $e) {
            $this->error("Backup failed: {$e->getMessage()}");
            Log::error('Database backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }

    /**
     * Create database backup
     */
    private function createBackup(): string
    {
        $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
        $filename = "backup_{$timestamp}.sql";
        $backupPath = storage_path("app/backups/{$filename}");
        
        // Ensure backup directory exists
        if (!file_exists(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0755, true);
        }

        $database = config('database.connections.mysql');
        
        $command = [
            'mysqldump',
            '--host=' . $database['host'],
            '--port=' . $database['port'],
            '--user=' . $database['username'],
            '--password=' . $database['password'],
            '--single-transaction',
            '--routines',
            '--triggers',
            '--add-drop-table',
            '--extended-insert',
            $database['database']
        ];

        $process = new Process($command);
        $process->setTimeout(3600); // 1 hour timeout
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \Exception('Backup process failed: ' . $process->getErrorOutput());
        }

        file_put_contents($backupPath, $process->getOutput());
        
        return $backupPath;
    }

    /**
     * Compress backup file
     */
    private function compressBackup(string $backupPath): string
    {
        $compressedPath = $backupPath . '.gz';
        
        $process = new Process(['gzip', '-9', $backupPath]);
        $process->run();
        
        if (!$process->isSuccessful()) {
            throw new \Exception('Compression failed: ' . $process->getErrorOutput());
        }
        
        return $compressedPath;
    }

    /**
     * Encrypt backup file
     */
    private function encryptBackup(string $backupPath): string
    {
        $encryptedPath = $backupPath . '.enc';
        $password = config('app.key');
        
        $process = new Process([
            'openssl', 'enc', '-aes-256-cbc', '-salt',
            '-in', $backupPath,
            '-out', $encryptedPath,
            '-pass', "pass:{$password}"
        ]);
        
        $process->run();
        
        if (!$process->isSuccessful()) {
            throw new \Exception('Encryption failed: ' . $process->getErrorOutput());
        }
        
        // Remove unencrypted file
        unlink($backupPath);
        
        return $encryptedPath;
    }

    /**
     * Clean old backup files (keep last 30 days)
     */
    private function cleanOldBackups(): void
    {
        $backupDir = storage_path('app/backups');
        $cutoffDate = Carbon::now()->subDays(30);
        
        if (!is_dir($backupDir)) {
            return;
        }
        
        $files = glob($backupDir . '/backup_*.{sql,gz,enc}', GLOB_BRACE);
        
        foreach ($files as $file) {
            if (filemtime($file) < $cutoffDate->timestamp) {
                unlink($file);
                $this->info("Deleted old backup: " . basename($file));
            }
        }
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        return round($bytes / (1024 ** $pow), 2) . ' ' . $units[$pow];
    }
}
