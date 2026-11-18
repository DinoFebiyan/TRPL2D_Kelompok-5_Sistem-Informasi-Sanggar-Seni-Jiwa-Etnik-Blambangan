<?php

namespace Tests\Feature\Backup;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Tests\TestCase;

class TC_BKP_001_BackupRestoreTest extends TestCase
{
    private string $temporaryPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->temporaryPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'jeb-backup-'.Str::uuid()->toString();

        File::makeDirectory($this->temporaryPath.DIRECTORY_SEPARATOR.'database', 0755, true);
        File::makeDirectory(
            $this->temporaryPath.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'backups',
            0755,
            true
        );

        // Arahkan database_path() dan storage_path() ke folder sementara agar
        // perintah backup/restore tidak menyentuh database aplikasi yang asli.
        $this->app->useDatabasePath($this->temporaryPath.DIRECTORY_SEPARATOR.'database');
        $this->app->useStoragePath($this->temporaryPath.DIRECTORY_SEPARATOR.'storage');

        $databaseFile = database_path('database.sqlite');
        File::put($databaseFile, '');

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', $databaseFile);

        DB::purge('sqlite');
        DB::reconnect('sqlite');

        $this->artisan('migrate', [
            '--database' => 'sqlite',
            '--force' => true,
            // Relatif terhadap base_path() karena opsi --path tidak menerima path absolut.
            '--path' => 'database/migrations',
        ])->assertExitCode(0);
    }

    protected function tearDown(): void
    {
        DB::purge('sqlite');
        File::deleteDirectory($this->temporaryPath);

        parent::tearDown();
    }

    public function test_bkp_001_backup_dan_restore_database(): void
    {
        $user = User::factory()->create();
        Event::factory()->count(2)->create(['user_id' => $user->id]);

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

        // Tutup koneksi agar berkas SQLite bisa ditimpa oleh perintah restore.
        DB::purge('sqlite');

        $this->artisan('db:sqlite-restore')
            ->assertExitCode(0);

        DB::reconnect('sqlite');

        $restoredCount = Event::count();
        $this->assertSame(
            $initialCount,
            $restoredCount,
            'Jumlah record setelah restore tidak sesuai.'
        );
    }
}
