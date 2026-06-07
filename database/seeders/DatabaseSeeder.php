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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat user Super Admin Utama
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@jeb.id',
            'password' => Hash::make('12345678'),
            'peran' => 'superadmin',
            'no_handphone' => '081234567890',
            'status' => 'aktif',
        ]);

        // 2. Buat user Admin Biasa
        $admin = User::create([
            'name' => 'Admin JEB',
            'email' => 'admin@jeb.id',
            'password' => Hash::make('12345678'),
            'peran' => 'admin',
            'no_handphone' => '081298765432',
            'status' => 'aktif',
        ]);

        // 3. Buat Akun Uji Coba OTP Poliwangi
        User::create([
            'name' => 'Poliwangi Admin',
            'email' => 'pblpoliwangi2026@gmail.com',
            'password' => Hash::make('kelompok5'),
            'peran' => 'superadmin',
            'no_handphone' => '081234567890',
            'status' => 'aktif',
        ]);

        // 4. Buat data Pengaturan Sistem (Link Sosial Media & Kontak Default JEB)
        PengaturanSistem::create([
            'link_maps' => 'https://maps.app.goo.gl/gYaB32Nd5wr9o83h8',
            'link_wa' => '6282232505355',
            'link_email' => 'mustikasadida@gmail.com',
            'link_instagram' => 'https://www.instagram.com/jiwaetnikblambangan',
            'link_tiktok' => 'https://www.tiktok.com/@jiwaetnikblambangan',
        ]);

        // 5. Buat data Galeri awal untuk Profil Sanggar (sumber_id diisi 0 dulu)
        $galeri1 = Galeri::create([
            'sumber_id' => 0,
            'path' => 'img/images-landing-page/galeri/logo-jeb.jpg',
            'nama_file' => uniqid().'_logo-jeb.jpg',
            'kategori' => 'profil',
            'is_watermark' => false,
        ]);

        $galeri2 = Galeri::create([
            'sumber_id' => 0,
            'path' => 'img/images-landing-page/galeri/foto_pembina.jpg',
            'nama_file' => uniqid().'_foto_pembina.jpg',
            'kategori' => 'profil',
            'is_watermark' => false,
        ]);

        // 6. Buat data Profil Sanggar
        $profil = ProfilSanggar::create([
            'deskripsi' => 'Sanggar Tari Jiwa Etnik Blambangan (JEB) adalah pusat seni tari tradisional di Banyuwangi.',
            'visi' => 'Menjadi sanggar seni yang unggul, berkarakter, and memiliki keunikan tersendiri.',
            'misi' => 'Mengembangkan potensi anggota dan melestarikan budaya Blambangan.',
            'sejarah' => 'Berdiri sejak 2010 di Kabupaten Banyuwangi...',
            'sambutan_pembina' => 'Selamat datang di JEB...',
            'logo_id' => $galeri1->id,
            'foto_pembina_id' => $galeri2->id,
        ]);

        // Sinkronisasi balik sumber_id ke ID profil sanggar
        $galeri1->update(['sumber_id' => $profil->id]);
        $galeri2->update(['sumber_id' => $profil->id]);

        // 7. Buat Personel (Pengurus & Pelatih) - FIX: Menghapus field ganda 'name' yang salah
        $pengurus = Personel::create([
            'nama' => 'Rizky Pratama',
            'jabatan' => 'Ketua Sanggar',
            'no_handphone' => '081234567890',
            'instagram' => '@rizkypratama',
            'peran' => 'pengurus',
            'status' => 'aktif',
        ]);

        Galeri::create([
            'sumber_id' => $pengurus->id,
            'path' => 'personel/dummy-pengurus.jpg',
            'nama_file' => uniqid().'_dummy-pengurus.jpg',
            'kategori' => 'profil',
            'is_watermark' => false,
        ]);

        $pelatih = Personel::create([
            'nama' => 'Budi Santoso',
            'jabatan' => 'Pelatih Tari',
            'no_handphone' => '081298765432',
            'instagram' => '@buditaridance',
            'peran' => 'pelatih',
            'status' => 'aktif',
        ]);

        Galeri::create([
            'sumber_id' => $pelatih->id,
            'path' => 'personel/dummy-pelatih.jpg',
            'nama_file' => uniqid().'_dummy-pelatih.jpg',
            'kategori' => 'profil',
            'is_watermark' => false,
        ]);

        // 8. SEEDER KATALOG: Sinkronisasi Multi-Foto
        for ($i = 1; $i <= 10; $i++) {
            $katalog = Katalog::create([
                'nama_katalog' => 'Kostum Tari '.$i,
                'kategori' => 'Tradisional',
                'stok' => 5,
                'deskripsi' => 'Deskripsi kostum tari ke-'.$i.'.',
                'status' => 'tersedia',
                'user_id' => $superAdmin->id,
            ]);

            Galeri::create([
                'sumber_id' => $katalog->id,
                'path' => 'img/images-landing-page/katalog/katalog_'.$i.'.jpeg',
                'nama_file' => uniqid().'_katalog_'.$i.'.jpeg',
                'kategori' => 'katalog',
                'is_watermark' => false,
            ]);
        }

        // 9. SEEDER BERITA
        for ($i = 1; $i <= 10; $i++) {
            $berita = Berita::create([
                'judul' => 'Update Berita Seni #'.$i,
                'isi_berita' => 'Konten berita ke-'.$i.' tentang kegiatan sanggar.',
                'jenis_berita' => 'Event',
                'status' => 'tayang',
                'user_id' => $admin->id,
            ]);

            Galeri::create([
                'sumber_id' => $berita->id,
                'path' => 'img/images-landing-page/berita/berita_'.$i.'.jpeg',
                'nama_file' => uniqid().'_berita_'.$i.'.jpeg',
                'kategori' => 'berita',
                'is_watermark' => false,
            ]);
        }

        // 10. SEEDER EVENT
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

            Galeri::create([
                'sumber_id' => $event->id,
                'path' => 'img/images-landing-page/event/event_'.$i.'.jpeg',
                'nama_file' => uniqid().'_event_'.$i.'.jpeg',
                'kategori' => 'event',
                'is_watermark' => false,
            ]);
        }
    }
}
