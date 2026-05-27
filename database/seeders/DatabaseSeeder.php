<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Berita;
use App\Models\Event;
use App\Models\Katalog;
use App\Models\Galeri;
use App\Models\Personel;
use App\Models\ProfilSanggar;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat user Super Admin
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@jeb.id',
            'password' => Hash::make('password'),
            'peran' => 'super_admin',
            'no_handphone' => '081234567890',
            'status' => 'aktif',
        ]);

        // 2. Buat user Admin biasa
        $admin = User::factory()->create([
            'name' => 'Admin JEB',
            'email' => 'admin@jeb.id',
            'password' => Hash::make('password'),
            'peran' => 'admin',
            'no_handphone' => '081298765432',
            'status' => 'aktif',
        ]);

        // 3. Buat data Galeri (foto) untuk Profil Sanggar
        $galeri1 = Galeri::create([
            'path' => 'dummy_base64_1',
            'nama_file' => 'logo_sanggar.jpg',
            'kategori' => 'logo',
            'is_watermark' => false,
        ]);

        $galeri2 = Galeri::create([
            'path' => 'dummy_base64_2',
            'nama_file' => 'foto_pembina.jpg',
            'kategori' => 'pembina',
            'is_watermark' => false,
        ]);

        // 4. Buat Profil Sanggar
        ProfilSanggar::create([
            'deskripsi' => 'Sanggar Tari Jiwa Etnik Blambangan (JEB) adalah pusat seni tari tradisional...',
            'visi' => 'Menjadi sanggar seni yang unggul, berkarakter, dan memiliki keunikan tersendiri...',
            'misi' => 'Mengembangkan potensi anggota...',
            'alamat' => 'Singojuruh, Banyuwangi',
            'kontak' => '082232505355',
            'sejarah' => 'Berdiri sejak 2010...',
            'sambutan_pembina' => 'Selamat datang di JEB...',
            'logo_id' => $galeri1->id,
            'foto_pembina_id' => $galeri2->id,
        ]);

        // 5. Buat Personel (Pengurus & Pelatih)
        Personel::create([
            'nama' => 'Rizky Pratama',
            'jabatan' => 'Ketua Sanggar',
            'no_handphone' => '081234567890',
            'instagram' => '@rizkypratama',
            'peran' => 'pengurus',
            'status' => 'aktif',
            'galeri_id' => null,
        ]);

        Personel::create([
            'nama' => 'Budi Santoso',
            'jabatan' => 'Pelatih Tari',
            'no_handphone' => '081298765432',
            'instagram' => '@buditaridance',
            'peran' => 'pelatih',
            'status' => 'aktif',
            'galeri_id' => null,
        ]);

        // 6. SEEDER KATALOG: Menyesuaikan Multi-Foto (sumber_id)
        for ($i = 1; $i <= 10; $i++) {
            $katalog = Katalog::create([
                'nama_katalog' => 'Kostum Tari ' . $i,
                'kategori' => 'Tradisional',
                'stok' => 5,
                'deskripsi' => 'Deskripsi kostum tari ke-' . $i . '.',
                'status' => 'tersedia',
                'user_id' => $superAdmin->id,
            ]);

            // Hubungkan rekaman foto ke tabel galeri menggunakan id katalog sebagai sumber_id
            Galeri::create([
                'sumber_id' => $katalog->id,
                'nama_file' => 'katalog_' . $i . '.jpeg',
                'path' => 'img/images-landing-page/katalog/katalog_' . $i . '.jpeg', 
                'kategori' => 'katalog',
                'is_watermark' => false,
            ]);
        }

        // 7. SEEDER BERITA: PERBAIKAN! Menggunakan pola sumber_id (bukan galeri_id lagi)
        for ($i = 1; $i <= 10; $i++) {
            $berita = Berita::create([
                'judul' => 'Update Berita Seni #' . $i,
                'isi_berita' => 'Konten berita ke-' . $i . ' tentang kegiatan sanggar.',
                'status' => 'tayang',
                'user_id' => $admin->id,
            ]);

            // Hubungkan rekaman foto ke tabel galeri menggunakan id berita sebagai sumber_id
            Galeri::create([
                'sumber_id' => $berita->id,
                'nama_file' => 'berita_' . $i . '.jpeg',
                'path' => 'img/images-landing-page/berita/berita_' . $i . '.jpeg',
                'kategori' => 'berita',
                'is_watermark' => false,
            ]);
        }

        // 8. Event (10 Data)
        for ($i = 1; $i <= 10; $i++) {
            Event::create([
                'nama_event' => 'Pentas Seni #' . $i,
                'tanggal'    => Carbon::now()->startOfMonth()->addDays($i + 10),
                'jam'        => '07:13:00',
                'lokasi'     => 'Pendopo Sabha Swagata Blambangan',
                'deskripsi'  => 'Penampilan dalam rangka Hari Jadi Kabupaten Banyuwangi ke-254...',
                
                // GANTI 'Pertunjukan' dengan salah satu nilai enum yang ada di migration event Anda!
                'kategori'   => 'Pendidikan', // Contoh jika di migration ada 'Festival'
                
                'status'     => 'belum selesai',
                'user_id'    => $admin->id,
            ]);          
        }
    }
}