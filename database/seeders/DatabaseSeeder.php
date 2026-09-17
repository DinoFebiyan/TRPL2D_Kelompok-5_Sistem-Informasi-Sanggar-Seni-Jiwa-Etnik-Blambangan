<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\Event;
use App\Models\Galeri;
use App\Models\Katalog;
use App\Models\PengaturanSistem;
use App\Models\Personel;
use App\Models\ProfilSanggar;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    private function copyDemoImage(string $srcPublicRelative, string $destStorageRelative): string
    {
        $src = public_path($srcPublicRelative);

        if (file_exists($src)) {
            Storage::disk('public')->put($destStorageRelative, file_get_contents($src));
            return $destStorageRelative;
        }

        return $srcPublicRelative;
    }

    /**
     * Helper untuk mengambil .env dan melempar error jika kosong.
     */
    private function getEnvOrThrow(string $key): string
    {
        $value = env($key);
        if (empty($value)) {
            throw new Exception("Seeder Gagal: Variabel {$key} tidak ditemukan atau kosong di file .env!");
        }
        return $value;
    }

    public function run(): void
    {
        // ====================================================================
        // 1. CEK ENVIRONMENT
        // ====================================================================
        // Mengambil nilai APP_ENV dari .env (bisa 'local', 'staging', 'production')
        $environment = app()->environment();
        $this->command->info("Menjalankan Seeder di Environment: " . strtoupper($environment));

        // ====================================================================
        // 2. SEEDER DATA INTI (Dijalankan di semua environment)
        // ====================================================================

        // Ambil data dari .env secara ketat (tanpa default/fallback hardcode)
        $superAdmin = User::updateOrCreate([
            'email' => $this->getEnvOrThrow('SUPERADMIN_EMAIL'),
        ], [
            'name' => $this->getEnvOrThrow('SUPERADMIN_NAME'),
            'password' => Hash::make($this->getEnvOrThrow('SUPERADMIN_PASSWORD')),
            'peran' => 'superadmin',
            'no_handphone' => '081234567890',
            'status' => 'aktif',
        ]);

        $admin = User::updateOrCreate([
            'email' => $this->getEnvOrThrow('ADMIN_EMAIL'),
        ], [
            'name' => $this->getEnvOrThrow('ADMIN_NAME'),
            'password' => Hash::make($this->getEnvOrThrow('ADMIN_PASSWORD')),
            'peran' => 'admin',
            'no_handphone' => '081298765432',
            'status' => 'aktif',
        ]);

        User::updateOrCreate([
            'email' => $this->getEnvOrThrow('OTP_EMAIL'),
        ], [
            'name' => $this->getEnvOrThrow('OTP_NAME'),
            'password' => Hash::make($this->getEnvOrThrow('OTP_PASSWORD')),
            'peran' => 'superadmin',
            'no_handphone' => '081234567890',
            'status' => 'aktif',
        ]);

        if (PengaturanSistem::count() === 0) {
            PengaturanSistem::create([
                'link_maps' => 'https://maps.app.goo.gl/gYaB32Nd5wr9o83h8',
                'link_wa' => '6282232505355',
                'link_email' => 'mustikasadida@gmail.com',
                'link_instagram' => 'https://www.instagram.com/jiwaetnikblambangan',
                'link_tiktok' => 'https://www.tiktok.com/@jiwaetnikblambangan',
            ]);
        }

        if (ProfilSanggar::count() === 0) {
            // Setup Profil Sanggar (Contoh data statis yang aman di-hardcode)
            $pathLogo = $this->copyDemoImage('img/images-landing-page/galeri/logo-jeb.jpg', 'galeri/logo-jeb.jpg');
            $pathPembina = $this->copyDemoImage('img/images-landing-page/galeri/foto_pembina.jpg', 'galeri/foto_pembina.jpg');

            $galeri1 = Galeri::create(['sumber_id' => 0, 'path' => $pathLogo, 'nama_file' => uniqid().'_logo-jeb.jpg', 'kategori' => 'profil', 'is_watermark' => false]);
            $galeri2 = Galeri::create(['sumber_id' => 0, 'path' => $pathPembina, 'nama_file' => uniqid().'_foto_pembina.jpg', 'kategori' => 'profil', 'is_watermark' => false]);

            $profil = ProfilSanggar::create([
                'deskripsi' => 'Sanggar Tari Jiwa Etnik Blambangan (JEB)...',
                'visi' => 'Menjadi sanggar seni yang unggul...',
                'misi' => 'Mengembangkan potensi anggota...',
                'sejarah' => 'Berdiri sejak 2010...',
                'sambutan_pembina' => 'Selamat datang di JEB...',
                'logo_id' => $galeri1->id,
                'foto_pembina_id' => $galeri2->id,
            ]);

            $galeri1->update(['sumber_id' => $profil->id]);
            $galeri2->update(['sumber_id' => $profil->id]);
        }

        // ====================================================================
        // 3. SEEDER DATA DUMMY (HANYA Dijalankan di Local/Dev)
        // ====================================================================
        
        if ($environment === 'local') {
            $this->command->info("Environment Local terdeteksi: Melakukan seeding data dummy (Katalog, Berita, Event)...");

            // SEEDER KATALOG DUMMY
            for ($i = 1; $i <= 10; $i++) {
                $katalog = Katalog::create([
                    'nama_katalog' => 'Kostum Tari '.$i,
                    'kategori' => 'Tradisional',
                    'stok' => 5,
                    'deskripsi' => 'Deskripsi kostum tari ke-'.$i.'.',
                    'status' => 'tersedia',
                    'user_id' => $superAdmin->id,
                ]);

                $pathKatalog = $this->copyDemoImage("img/images-landing-page/katalog/katalog_{$i}.jpeg", "katalog/katalog_{$i}.jpeg");
                Galeri::create(['sumber_id' => $katalog->id, 'path' => $pathKatalog, 'nama_file' => uniqid()."_katalog_{$i}.jpeg", 'kategori' => 'katalog', 'is_watermark' => false]);
            }

            // SEEDER BERITA DUMMY
            for ($i = 1; $i <= 10; $i++) {
                $berita = Berita::create([
                    'judul' => 'Update Berita Seni #'.$i,
                    'isi_berita' => 'Konten berita ke-'.$i.' tentang kegiatan sanggar.',
                    'jenis_berita' => 'Event',
                    'status' => 'tayang',
                    'user_id' => $admin->id,
                ]);

                $pathBerita = $this->copyDemoImage("img/images-landing-page/berita/berita_{$i}.jpeg", "berita/berita_{$i}.jpeg");
                Galeri::create(['sumber_id' => $berita->id, 'path' => $pathBerita, 'nama_file' => uniqid()."_berita_{$i}.jpeg", 'kategori' => 'berita', 'is_watermark' => false]);
            }

            // SEEDER EVENT DUMMY
            for ($i = 1; $i <= 10; $i++) {
                $event = Event::create([
                    'nama' => 'Pentas Seni #'.$i,
                    'tanggal' => Carbon::now()->startOfMonth()->addDays($i + 10),
                    'jam' => '19:00:00',
                    'lokasi' => 'Pendopo Sabha Swagata Blambangan',
                    'deskripsi' => 'Penampilan dalam rangka Hari Jadi Kabupaten Banyuwangi...',
                    'kategori' => 'Latihan',
                    'status' => 'belum selesai',
                    'user_id' => $admin->id,
                ]);

                $pathEvent = $this->copyDemoImage("img/images-landing-page/event/event_{$i}.jpeg", "event/event_{$i}.jpeg");
                Galeri::create(['sumber_id' => $event->id, 'path' => $pathEvent, 'nama_file' => uniqid()."_event_{$i}.jpeg", 'kategori' => 'event', 'is_watermark' => false]);
            }
        } else {
            // Jika di Staging atau Production, skip data dummy
            $this->command->warn("Environment bukan Local: Melewati seeding data dummy untuk menjaga database tetap bersih.");
        }
    }
}