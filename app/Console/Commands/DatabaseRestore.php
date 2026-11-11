<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DatabaseRestore extends Command
{
    /**
     * Nama command.
     */
    protected $signature = 'db:sqlite-restore';

    /**
     * Deskripsi command.
     */
    protected $description = 'Restore database SQLite dari backup terbaru';

    public function handle()
    {
        $backupDir = storage_path('app/backups');
        $databasePath = database_path('database.sqlite');

        if (!File::exists($backupDir)) {
            $this->error('Folder backup tidak ditemukan.');

            return self::FAILURE;
        }

        $backupFiles = File::files($backupDir);

        if (empty($backupFiles)) {
            $this->error('Tidak ada file backup.');

            return self::FAILURE;
        }

        usort($backupFiles, function ($a, $b) {
            return $b->getMTime() <=> $a->getMTime();
        });

        $latestBackup = $backupFiles[0];

        File::copy(
            $latestBackup->getPathname(),
            $databasePath
        );

        $this->info(
            'Database berhasil direstore dari: ' .
            $latestBackup->getFilename()
        );

        return self::SUCCESS;
    }
}