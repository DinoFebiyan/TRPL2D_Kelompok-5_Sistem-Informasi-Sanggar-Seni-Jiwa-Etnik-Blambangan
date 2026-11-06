<?php

namespace Tests\Feature\Backup;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\File;

class DatabaseBackupTest extends TestCase
{
    public function test_database_backup_and_restore()
    {

        $this->artisan('db:sqlite-backup')
            ->assertExitCode(0);

        $backupDir = storage_path('app/backups');

        $backupFiles = File::files($backupDir);

        $this->assertNotEmpty(
            $backupFiles,
            'Berkas cadangan gagal dibuat.'
        );

        $latestBackup = end($backupFiles);

        $this->assertGreaterThan(
            0,
            File::size($latestBackup->getPathname()),
            'Ukuran berkas backup harus lebih dari 0.'
        );

        $initialCount = Event::count();

        Event::query()->delete();

        $afterDeleteCount = Event::count();

        $this->assertLessThan(
            $initialCount,
            $afterDeleteCount,
            'Jumlah record tidak berkurang setelah penghapusan.'
        );

        dump($initialCount, $afterDeleteCount);

        $this->artisan('db:sqlite-restore')
            ->assertExitCode(0);

            dump(
    config('database.connections.sqlite.database')
);

        $restoredCount = Event::count();
        dump($restoredCount);
        $this->assertSame(
            $initialCount,
            $restoredCount,
            'Jumlah record setelah restore tidak sesuai.'
        );
    }
}
