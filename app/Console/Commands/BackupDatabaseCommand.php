<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackupDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup the database and remove old backups';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Create the backups directory if it doesn't exist
        $this->createBackupDirectory();

        // Backup the database
        $this->backupDatabase();

        // Remove backups older than 3 months
        $this->removeOldBackups();
    }

    protected function createBackupDirectory()
    {
        $backupPath = storage_path('app/backups');

        // Check if the directory exists; if not, create it
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
            $this->info('Backup directory created at ' . $backupPath);
        } else {
            $this->info('Backup directory already exists at ' . $backupPath);
        }
    }

    protected function backupDatabase()
    {
        $database = env('DB_DATABASE');
        $user = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST');
        $backupFile = storage_path("app/backups/{$database}_" . date('YmdHis') . '.sql');
        $compressedBackupFile = storage_path("app/backups/{$database}_" . date('YmdHis') . '.sql.gz');

        // Step 1: Backup the database (create the .sql file)
        $command = "mysqldump --user={$user} --password={$password} --host={$host} {$database} > {$backupFile}";

        system($command, $output);

        if ($output === 0) {
            $this->info('Database backup successfully created at ' . $backupFile);

            // Step 2: Compress the .sql file to .gz
            $gzipCommand = "gzip -c {$backupFile} > {$compressedBackupFile}";
            system($gzipCommand, $gzipOutput);

            if ($gzipOutput === 0) {
                // Step 3: Optionally, delete the uncompressed .sql file
                File::delete($backupFile);
                $this->info('Backup compressed successfully and stored at ' . $compressedBackupFile);
            } else {
                $this->error('Error during compression process.');
            }
        } else {
            $this->error('Error during backup process.');
        }
    }

    protected function removeOldBackups()
    {
        $backupPath = storage_path('app/backups');
        $files = File::files($backupPath);

        // Arrays to hold the backups organized by month and year
        $monthlyBackups = [];
        $yearlyBackups = [];

        foreach ($files as $file) {
            // Get the file extension and file creation time
            $fileExtension = $file->getExtension();
            $fileCreationTime = \Carbon\Carbon::createFromTimestamp($file->getCTime());

            // Skip the file if it is not a .sql or .sql.gz file
            if (!in_array($fileExtension, ['sql', 'gz'])) {
                continue;
            }

            // Check if the file is within the last 30 days (keep daily backups for the last 30 days)
            if ($fileCreationTime->diffInDays(now()) <= 30) {
                // Keep backups created in the last 30 days (daily backups)
                $this->info('Backup kept (within last 30 days): ' . $file->getFilename());
                continue;
            }

            // Check if the file is older than 1 year (365 days)
            if ($fileCreationTime->diffInDays(now()) > 365) {
                // For files older than 1 year, keep one backup for each year
                $year = $fileCreationTime->year;
                if (!isset($yearlyBackups[$year])) {
                    // Keep the first backup of the year
                    $yearlyBackups[$year] = $file;
                    $this->info('Backup kept (yearly backup for ' . $year . '): ' . $file->getFilename());
                } else {
                    // Delete all backups older than 1 year except the first one per year
                    File::delete($file);
                    $this->info('Deleted old backup (older than 1 year): ' . $file->getFilename());
                }
                continue;
            }

            // For backups older than 30 days but within the last year
            $month = $fileCreationTime->format('Y-m'); // Format as Year-Month (e.g., 2024-06)
            if (!isset($monthlyBackups[$month])) {
                // Keep one backup per month
                $monthlyBackups[$month] = $file;
                $this->info('Backup kept (monthly backup for ' . $fileCreationTime->format('F Y') . '): ' . $file->getFilename());
            } else {
                // Delete backups that are duplicate for the same month
                File::delete($file);
                $this->info('Deleted old backup (duplicate month): ' . $file->getFilename());
            }
        }
    }
}
