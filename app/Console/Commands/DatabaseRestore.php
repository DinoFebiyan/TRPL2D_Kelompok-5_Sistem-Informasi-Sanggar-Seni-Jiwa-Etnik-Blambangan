<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use ZipArchive;

class DatabaseRestore extends Command
{
    protected $signature = 'db:restore {--file= : Nama file zip backup} {--force : Paksa tanpa konfirmasi}';
    protected $description = 'Restore database + media dari file .zip';

    public function handle()
    {
        $requestedFile = $this->option('file');
        if (!$requestedFile) { $this->error('File harus ditentukan.'); return self::FAILURE; }

        $safeFilename = basename($requestedFile);
        $zipPath = storage_path("app/backups/{$safeFilename}");
        if (!File::exists($zipPath) || pathinfo($zipPath, PATHINFO_EXTENSION) !== 'zip') {
            $this->error('File zip tidak ditemukan.'); return self::FAILURE;
        }

        if (!$this->option('force') && !$this->confirm('Restore akan menimpa data saat ini. Lanjutkan?')) return self::SUCCESS;

        $tempDir = storage_path('app/restore-temp-' . uniqid());
        File::makeDirectory($tempDir, 0755, true);

        $zip = new ZipArchive();
        if ($zip->open($zipPath) !== true) { $this->error('Gagal membuka zip.'); return self::FAILURE; }
        $zip->extractTo($tempDir);
        $zip->close();

        // 1. Restore DB
        $connection = config('database.default');
        if (in_array($connection, ['mysql', 'mariadb'])) {
            $dbConfig = config("database.connections.{$connection}");
            $sqlContent = File::get("{$tempDir}/database.sql");
            Process::env(['MYSQL_PWD' => $dbConfig['password'] ?? ''])->input($sqlContent)->run([
                'mysql', '-h', $dbConfig['host'] ?? '127.0.0.1', '-P', (string) ($dbConfig['port'] ?? 3306),
                '-u', $dbConfig['username'], $dbConfig['database'],
            ]);
        } else {
            File::copy("{$tempDir}/database.sqlite", config('database.connections.sqlite.database') ?: database_path('database.sqlite'));
        }

        // 2. Restore Media
        if (File::exists("{$tempDir}/storage")) {
            File::deleteDirectory(storage_path('app/public'));
            File::copyDirectory("{$tempDir}/storage", storage_path('app/public'));
        }

        File::deleteDirectory($tempDir);
        $this->info('Restore berhasil.');
        return self::SUCCESS;
    }
}
