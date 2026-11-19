<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use ZipArchive;

class DatabaseBackup extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup database (MySQL/SQLite) dan file publik (.zip)';

    public function handle()
    {
        $connection = config('database.default');
        $backupDir = storage_path('app/backups');
        $tempDir = storage_path('app/backup-temp-' . uniqid());

        if (!File::exists($backupDir)) File::makeDirectory($backupDir, 0755, true);
        File::makeDirectory($tempDir, 0755, true);

        $timestamp = date('Y-m-d_H-i-s');
        $zipFilename = "backup-{$timestamp}.zip";
        $zipPath = "{$backupDir}/{$zipFilename}";

        // 1. Dump DB
        $dbFile = in_array($connection, ['mysql', 'mariadb']) ? 'database.sql' : 'database.sqlite';
        $dbDumpPath = "{$tempDir}/{$dbFile}";

        if (in_array($connection, ['mysql', 'mariadb'])) {
            $dbConfig = config("database.connections.{$connection}");
            $result = Process::env(['MYSQL_PWD' => $dbConfig['password'] ?? ''])->run([
                'mysqldump', '-h', $dbConfig['host'] ?? '127.0.0.1', '-P', (string) ($dbConfig['port'] ?? 3306),
                '-u', $dbConfig['username'], '--single-transaction', '--quick', $dbConfig['database'], "--result-file={$dbDumpPath}",
            ]);
            if ($result->failed()) { $this->error('DB Dump gagal'); File::deleteDirectory($tempDir); return self::FAILURE; }
        } else {
            File::copy(config('database.connections.sqlite.database') ?: database_path('database.sqlite'), $dbDumpPath);
        }

        // 2. Zip Everything
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->error('Gagal buat zip'); File::deleteDirectory($tempDir); return self::FAILURE;
        }

        $zip->addFile($dbDumpPath, $dbFile);

        $publicPath = storage_path('app/public');
        if (File::exists($publicPath)) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($publicPath));
            foreach ($files as $file) {
                if ($file->isDir()) continue;
                $relativePath = 'storage/' . substr($file->getPathname(), strlen($publicPath) + 1);
                $zip->addFile($file->getPathname(), $relativePath);
            }
        }
        $zip->close();

        // 3. Cleanup
        File::deleteDirectory($tempDir);
        $oneMonthAgo = now()->subMonth()->timestamp;
        foreach (File::files($backupDir) as $file) {
            if ($file->getMTime() < $oneMonthAgo) File::delete($file->getPathname());
        }

        $this->info("Backup lengkap berhasil dibuat: {$zipFilename}");
        return self::SUCCESS;
    }
}
