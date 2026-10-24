<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DatabaseBackup extends Command
{
    protected $signature = 'db:sqlite-backup';
    protected $description = 'Melakukan backup database SQLite mingguan dan menghapus backup berumur lebih dari 1 bulan';

    public function handle()
    {
        $databasePath = database_path('database.sqlite');
        $backupDir = storage_path('app/backups');

        // Pastikan folder backup tersedia
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        // 1. Proses Backup (Membuat salinan file dengan format nama tanggal)
        $filename = 'backup-' . date('Y-m-d_H-i-s') . '.sqlite';
        $backupPath = $backupDir . '/' . $filename;

        if (File::exists($databasePath)) {
            File::copy($databasePath, $backupPath);
            $this->info("Backup berhasil dibuat: {$filename}");
        } else {
            $this->error("File database SQLite tidak ditemukan!");
            return;
        }

        // 2. Pembersihan Otomatis (Hapus file yang umurnya > 1 bulan / 30 hari)
        $files = File::files($backupDir);
        $oneMonthAgo = now()->subMonth()->timestamp;

        foreach ($files as $file) {
            if ($file->getMTime() < $oneMonthAgo) {
                File::delete($file->getPathname());
                $this->info("Backup lama dihapus: " . $file->getFilename());
            }
        }
    }
}
