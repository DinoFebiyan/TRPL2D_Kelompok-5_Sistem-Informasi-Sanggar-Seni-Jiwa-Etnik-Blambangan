<?php

namespace Tests\Feature\Ai;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\BeritaTestCase;

/**
 * TC-AI-002 (Feature, Sedang) - Draft AI dapat diedit manual dan baru
 * tersimpan/tayang setelah tombol Simpan (AI tidak mempublikasikan otomatis).
 */
class DraftAiDisimpanSetelahSimpanTest extends BeritaTestCase
{
    private const JUDUL_EDIT = 'Pentas Gandrung Meriahkan Festival Budaya';
    private const ISI_TAMBAHAN = 'Sanggar JEB mengajak generasi muda melestarikan budaya lokal.';

    public function test_draft_ai_baru_tampil_di_publik_setelah_diedit_dan_disimpan(): void
    {
        config(['services.gemini.key' => 'kunci-uji']);
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response(
            $this->bodyGemini('<h3>Judul draft AI</h3><p>Paragraf draft AI tentang pentas Gandrung.</p>'), 200
        )]);

        $admin = $this->buatAdmin();
        $this->actingAs($admin);

        // Prasyarat: draft AI dihasilkan (TC-AI-001)
        $draft = $this->post(route('generate.ai.berita'), [
            'judul' => '', 'jenis_berita' => 'Event', 'prompt' => '', 'foto' => [$this->foto('foto_event_gandrung.jpg')],
        ], ['Accept' => 'application/json'])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->json('content');

        // Langkah 2: sebelum Simpan, berita belum ada & belum tampil di publik
        $this->assertDatabaseCount('berita', 0);
        $this->assertSame(0, $this->get(route('umum.berita'))->viewData('beritas')->total());

        // Langkah 1 & 3: edit manual lalu Simpan
        $this->post(route('admin.store-berita'), [
            'judul' => self::JUDUL_EDIT,
            'isi' => $draft.'<p>'.self::ISI_TAMBAHAN.'</p>',
            'jenis_berita' => 'Event',
            'status' => 'tayang',
            'foto' => [$this->foto('foto_event_gandrung.jpg')],
        ])->assertRedirect(route('admin.kelola-berita'))->assertSessionHasNoErrors();

        $berita = Berita::where('judul', self::JUDUL_EDIT)->firstOrFail();
        $this->assertSame('tayang', $berita->status);
        $this->assertStringContainsString(self::ISI_TAMBAHAN, $berita->isi_berita);

        $foto = Galeri::where('sumber_id', $berita->id)->where('kategori', 'berita')->firstOrFail();
        $this->assertTrue((bool) $foto->is_watermark);
        Storage::disk('public')->assertExists($foto->path);

        $this->assertTrue(LogAktivitas::where('aktivitas', 'Menambahkan berita baru: '.self::JUDUL_EDIT)->exists());

        // Langkah 4: pengunjung melihat hasil edit manual
        $this->keluarSebagaiPengunjung();
        $this->get(route('umum.berita'))->assertOk()->assertSee(self::JUDUL_EDIT);
        $this->get(route('umum.berita-detail', $berita->id))
            ->assertOk()
            ->assertSee(self::JUDUL_EDIT)
            ->assertSee(self::ISI_TAMBAHAN);
    }

    public function test_pemanggilan_generate_ai_tidak_membuat_berita_apa_pun(): void
    {
        config(['services.gemini.key' => 'kunci-uji']);
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response($this->bodyGemini('<p>Draft</p>'), 200)]);

        $this->actingAs($this->buatAdmin())
            ->post(route('generate.ai.berita'), ['foto' => [$this->foto()]], ['Accept' => 'application/json'])
            ->assertOk();

        $this->assertDatabaseCount('berita', 0);
        $this->assertDatabaseCount('galeri', 0);
    }

    public function test_endpoint_generate_ai_menolak_pengunjung_tanpa_login(): void
    {
        Http::fake();

        $this->postJson(route('generate.ai.berita'), [])->assertUnauthorized();

        Http::assertNothingSent();
    }
}
