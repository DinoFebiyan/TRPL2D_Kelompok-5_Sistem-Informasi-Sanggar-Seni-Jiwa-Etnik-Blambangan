<?php

namespace Tests\Unit\Ai;

use App\Http\Controllers\BeritaController;
use App\Models\Berita;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Tests\BeritaTestCase;

/**
 * TC-AI-004 (Unit, Sulit) - Form prompt penyesuaian & pembaruan draft iteratif.
 */
class TC_AI_004_PromptPenyesuaianDraftTest extends BeritaTestCase
{
    private const PROMPT_1 = 'Buat kalimat lebih singkat, formal, dan tambahkan penekanan pada pelestarian budaya lokal';
    private const PROMPT_2 = 'Tambahkan satu paragraf penutup yang mengajak masyarakat menonton';

    private BeritaController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->buatAdmin());
        config(['services.gemini.key' => 'kunci-uji']);
        $this->controller = app(BeritaController::class);
    }

    private function revisi(string $prompt): \Illuminate\Http\JsonResponse
    {
        return $this->controller->generateAiBerita($this->requestDengan(
            ['judul' => '', 'jenis_berita' => 'Event', 'prompt' => $prompt],
            ['foto' => [$this->foto()]]
        ));
    }

    private function teksPrompt(HttpRequest $r): string
    {
        return $r->data()['contents'][0]['parts'][0]['text'];
    }

    public function test_ai_004_langkah1_3_prompt_dikirim_ke_gemini_dan_draft_diperbarui(): void
    {
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response($this->bodyGemini('<p>Draft revisi 1</p>'), 200)]);

        $json = $this->revisi(self::PROMPT_1)->getData(true);

        $this->assertTrue($json['success']);
        $this->assertSame('<p>Draft revisi 1</p>', $json['content']);
        Http::assertSent(fn (HttpRequest $r) => str_contains($this->teksPrompt($r), self::PROMPT_1)
            && str_contains($this->teksPrompt($r), 'Catatan Perbaikan'));
    }

    public function test_ai_004_langkah4_5_revisi_iteratif_belum_tersimpan(): void
    {
        Http::fake(['generativelanguage.googleapis.com/*' => Http::sequence()
            ->push($this->bodyGemini('<p>Draft revisi 1</p>'), 200)
            ->push($this->bodyGemini('<p>Draft revisi 2 + penutup</p>'), 200),
        ]);

        $pertama = $this->revisi(self::PROMPT_1)->getData(true);
        $kedua = $this->revisi(self::PROMPT_2)->getData(true);

        $this->assertSame('<p>Draft revisi 1</p>', $pertama['content']);
        $this->assertSame('<p>Draft revisi 2 + penutup</p>', $kedua['content']);
        $this->assertNotSame($pertama['content'], $kedua['content']);

        Http::assertSentCount(2);
        $dikirim = Http::recorded()->map(fn ($pair) => $this->teksPrompt($pair[0]))->values();
        $this->assertStringContainsString(self::PROMPT_1, $dikirim[0]);
        $this->assertStringContainsString(self::PROMPT_2, $dikirim[1]);
        $this->assertStringNotContainsString(self::PROMPT_1, $dikirim[1]);

        // draft belum tersimpan sebelum tombol Simpan
        $this->assertSame(0, Berita::count());
    }

    public function test_ai_004_prompt_dipangkas_spasi_di_awal_akhir(): void
    {
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response($this->bodyGemini('<p>x</p>'), 200)]);

        $this->revisi("   Perpendek paragraf pertama   ");

        Http::assertSent(fn (HttpRequest $r) => str_contains($this->teksPrompt($r), "\"Perpendek paragraf pertama\""));
    }

    public function test_ai_004_langkah6_prompt_kosong_tanpa_catatan_perbaikan(): void
    {
        // Perilaku backend saat ini: prompt kosong = generate biasa tanpa blok
        // "Catatan Perbaikan". Validasi 'Prompt penyesuaian wajib diisi' dan tombol
        // 'Terapkan Penyesuaian' tidak ada di backend (lihat laporan, catatan TC-AI-004).
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response($this->bodyGemini('<p>x</p>'), 200)]);

        $this->revisi('   ');

        Http::assertSent(fn (HttpRequest $r) => ! str_contains($this->teksPrompt($r), 'Catatan Perbaikan'));
    }
}
