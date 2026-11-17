<?php

namespace Tests\Feature\Profil;

use App\Models\Personel;
use App\Models\ProfilSanggar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TC_PRF_003_ProfilPublikTest extends TestCase
{
    use RefreshDatabase;

    public function test_prf_003_profil_publik_menampilkan_profil_pengurus_pelatih_prestasi()
    {
        ProfilSanggar::create([
            'deskripsi' => 'Sanggar JEB adalah sanggar seni Gandrung Blambangan.',
            'visi' => 'Visi melestarikan budaya.',
            'misi' => 'Misi melatih anak muda.',
            'sejarah' => 'Berdiri sejak 1975.',
            'sambutan_pembina' => 'Salam budaya untuk semuanya.',
        ]);

        Personel::create([
            'nia' => 'P-001',
            'nama' => 'Surya Widodo',
            'jabatan' => 'Ketua Sanggar',
            'no_handphone' => '081234567890',
            'peran' => 'pengurus',
            'status' => 'aktif',
            'prestasi' => 'Juara 1 Festival Gandrung 2025',
        ]);

        Personel::create([
            'nia' => 'L-001',
            'nama' => 'Rina Ayu Pelatih',
            'jabatan' => 'Pelatih Tari',
            'no_handphone' => '082234567890',
            'peran' => 'pelatih',
            'status' => 'aktif',
            'prestasi' => 'Pelatih terbaik Jatim 2024',
        ]);

        $response = $this->get(route('umum.profil'));

        $response->assertStatus(200);
        $response->assertSee('Sanggar JEB adalah sanggar seni Gandrung Blambangan.');
        $response->assertSee('Salam budaya untuk semuanya.');
        $response->assertSee('Surya Widodo');
        $response->assertSee('Ketua Sanggar');
        $response->assertSee('Rina Ayu Pelatih');
        $response->assertSee('Juara 1 Festival Gandrung 2025');
        $response->assertSee('Pelatih terbaik Jatim 2024');
    }

    public function test_prf_003_profil_publik_filter_peran_dan_pencarian()
    {
        Personel::create([
            'nia' => 'P-002',
            'nama' => 'Budi Santoso',
            'jabatan' => 'Sekretaris',
            'no_handphone' => '081200000001',
            'peran' => 'pengurus',
            'status' => 'aktif',
        ]);

        Personel::create([
            'nia' => 'L-002',
            'nama' => 'Citra Larasati',
            'jabatan' => 'Pelatih',
            'no_handphone' => '081200000002',
            'peran' => 'pelatih',
            'status' => 'aktif',
        ]);

        $response = $this->get(route('umum.profil', ['peran' => ['pelatih']]));
        $response->assertStatus(200);
        $response->assertSee('Citra Larasati');
        $response->assertDontSee('Budi Santoso');

        $response = $this->get(route('umum.profil', ['search' => 'Budi']));
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertDontSee('Citra Larasati');
    }
}
