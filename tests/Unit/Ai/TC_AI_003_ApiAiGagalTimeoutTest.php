<?php

namespace Tests\Unit\Ai;

use App\Http\Controllers\BeritaController;
use App\Models\Berita;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Tests\BeritaTestCase;

/**
 * TC-AI-003 (Unit, Sulit) - Kegagalan / timeout API LLM:
 * pesan informatif, tanpa error tak tertangani, penulisan manual tetap jalan.
 */
class TC_AI_003_ApiAiGagalTimeoutTest extends BeritaTestCase
{
    private const JUMLAH_MODEL_FALLBACK = 6;
    private const PESAN_TC = 'Gagal menghasilkan draft, silakan coba lagi atau tulis berita secara manual';

    private BeritaController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->buatAdmin());
        config(['services.gemini.key' => 'kunci-uji']);
        $this->controller = app(BeritaController::class);
    }

    private function generate(): \Illuminate\Http\JsonResponse
    {
        return $this->controller->generateAiBerita($this->requestDengan(
            ['judul' => '', 'jenis_berita' => 'Event', 'prompt' => ''],
            ['foto' => [$this->foto()]]
        ));
    }

    private function assertGagalTerkendali(\Illuminate\Http\JsonResponse $res): void
    {
        $json = $res->getData(true);

        $this->assertFalse($json['success']);
        $this->assertArrayNotHasKey('content', $json);
        $this->assertNotEmpty($json['message']);
        $this->assertStringNotContainsString('Stack trace', $json['message']);
        $this->assertStringNotContainsString('.php', $json['message']);
    }

    // --- Kondisi 1: timeout ------------------------------------------------

    public function test_ai_003_langkah1_timeout_ditangani_dengan_pesan(): void
    {
        Http::fake(function () {
            throw new ConnectionException('cURL error 28: Operation timed out');
        });

        $this->assertGagalTerkendali($this->generate());
    }

    // --- Kondisi 2: kuota habis -------------------------------------------

    public function test_ai_003_langkah2_kuota_habis_ditangani(): void
    {
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response(
            ['error' => ['code' => 429, 'message' => 'Quota exceeded', 'status' => 'RESOURCE_EXHAUSTED']], 429
        )]);

        $this->assertGagalTerkendali($this->generate());
        Http::assertSentCount(self::JUMLAH_MODEL_FALLBACK);
        $this->assertSame(0, Berita::count());
    }

    public function test_ai_003_kuota_model_pertama_beralih_model_berikutnya(): void
    {
        Http::fake(['generativelanguage.googleapis.com/*' => Http::sequence()
            ->push(['error' => ['code' => 429]], 429)
            ->push($this->bodyGemini('<p>Draft dari model kedua</p>'), 200),
        ]);

        $json = $this->generate()->getData(true);

        $this->assertTrue($json['success']);
        $this->assertSame('<p>Draft dari model kedua</p>', $json['content']);
        Http::assertSentCount(2);
        Http::assertSent(fn (HttpRequest $r) => str_contains($r->url(), 'gemini-2.5-pro'));
    }

    // --- Kondisi 3: respons kosong / JSON rusak / tanpa judul-isi ----------

    public static function responsRusak(): array
    {
        return [
            'respons kosong' => [''],
            'JSON rusak' => ['{"candidates": [ {rusak'],
            'candidates kosong' => ['{"candidates":[]}'],
            'tanpa field text' => ['{"candidates":[{"content":{"parts":[{}]}}]}'],
            'text kosong' => ['{"candidates":[{"content":{"parts":[{"text":""}]}}]}'],
        ];
    }

    #[DataProvider('responsRusak')]
    public function test_ai_003_langkah5_respons_rusak_sebagai_kegagalan(string $body): void
    {
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response($body, 200)]);

        $this->assertGagalTerkendali($this->generate());
        Http::assertSentCount(self::JUMLAH_MODEL_FALLBACK);
    }

    // --- Pemulihan & fallback manual --------------------------------------

    public function test_ai_003_langkah4_layanan_pulih_tanpa_reload(): void
    {
        $seq = Http::sequence();
        for ($i = 0; $i < self::JUMLAH_MODEL_FALLBACK; $i++) {
            $seq->push(['error' => 'down'], 503);
        }
        $seq->push($this->bodyGemini('<p>Sudah pulih</p>'), 200);
        Http::fake(['generativelanguage.googleapis.com/*' => $seq]);

        $this->assertFalse($this->generate()->getData(true)['success']);

        $pulih = $this->generate()->getData(true);
        $this->assertTrue($pulih['success']);
        $this->assertSame('<p>Sudah pulih</p>', $pulih['content']);
    }

    public function test_ai_003_langkah3_penulisan_manual_tetap_berjalan(): void
    {
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response([], 500)]);
        $this->assertFalse($this->generate()->getData(true)['success']);

        $this->controller->store($this->requestDengan(
            $this->dataBerita([
                'judul' => 'Berita Manual Setelah Gagal AI',
                'isi' => 'Penulisan manual tetap berjalan.',
            ]),
            ['foto' => [$this->foto()]]
        ));

        $this->assertDatabaseHas('berita', [
            'judul' => 'Berita Manual Setelah Gagal AI',
            'status' => 'tayang',
        ]);
    }

    public function test_ai_003_api_key_belum_dikonfigurasi_ditangani(): void
    {
        config(['services.gemini.key' => null]);
        Http::fake();

        $res = $this->generate();

        $this->assertFalse($res->getData(true)['success']);
        $this->assertStringContainsString('GEMINI_API_KEY', $res->getData(true)['message']);
        Http::assertNothingSent();
    }

    // --- Selisih terhadap spesifikasi -------------------------------------

    #[Group('selisih-spesifikasi')]
    public function test_ai_003_pesan_kegagalan_sesuai_test_case_bukan_error_500(): void
    {
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response([], 429)]);

        $res = $this->generate();

        $this->assertNotSame(500, $res->getStatusCode(), 'TC: tanpa error 500.');
        $this->assertSame(self::PESAN_TC, $res->getData(true)['message']);
    }
}
