<?php

namespace Tests\Unit\Ai;

use App\Http\Controllers\BeritaController;
use App\Models\Berita;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Group;
use Tests\BeritaTestCase;

/**
 * TC-AI-001 (Unit, Sulit) - Generate draft berita dari foto lewat Gemini.
 * Gemini API di-mock memakai Http::fake(), tidak ada request nyata.
 */
class TC_AI_001_GenerateDraftGeminiTest extends BeritaTestCase
{
    private BeritaController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs($this->buatAdmin());
        config(['services.gemini.key' => 'kunci-uji']);
        $this->controller = app(BeritaController::class);
    }

    private function requestAi(array $data = [], array $foto = []): \Illuminate\Http\Request
    {
        return $this->requestDengan(
            array_merge(['judul' => '', 'jenis_berita' => 'Event', 'prompt' => ''], $data),
            $foto ? ['foto' => $foto] : []
        );
    }

    public function test_ai_001_langkah2_3_foto_valid_dikirim_ke_gemini(): void
    {
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response(
            $this->bodyGemini("```html\n<h3>Pentas Gandrung Meriah</h3><p>Isi draft AI.</p>\n```"), 200
        )]);

        $foto = $this->foto('foto_event_gandrung.jpg', 1572864); // JPG valid 1,5 MB
        $base64 = base64_encode(file_get_contents($foto->getRealPath()));

        $res = $this->controller->generateAiBerita($this->requestAi([], [$foto]));
        $json = $res->getData(true);

        $this->assertSame(200, $res->getStatusCode());
        $this->assertTrue($json['success']);
        // pagar ```html dibuang & hasil di-trim
        $this->assertSame('<h3>Pentas Gandrung Meriah</h3><p>Isi draft AI.</p>', $json['content']);

        Http::assertSentCount(1);
        Http::assertSent(function (HttpRequest $r) use ($base64) {
            $parts = $r->data()['contents'][0]['parts'];

            return str_contains($r->url(), 'generativelanguage.googleapis.com')
                && str_contains($r->url(), ':generateContent')
                && str_contains($r->url(), 'key=kunci-uji')
                && $parts[1]['inline_data']['mime_type'] === 'image/jpeg'
                && $parts[1]['inline_data']['data'] === $base64;
        });
    }

    public function test_ai_001_draft_tidak_dipublikasikan_otomatis(): void
    {
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response($this->bodyGemini('<p>Draft</p>'), 200)]);

        $this->controller->generateAiBerita($this->requestAi([], [$this->foto()]));

        $this->assertSame(0, Berita::count());
    }

    public function test_ai_001_judul_dan_jenis_diteruskan_ke_prompt(): void
    {
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response($this->bodyGemini('<p>x</p>'), 200)]);

        $this->controller->generateAiBerita($this->requestAi(
            ['judul' => 'Pentas Gandrung', 'jenis_berita' => 'Prestasi'], [$this->foto()]
        ));

        Http::assertSent(function (HttpRequest $r) {
            $teks = $r->data()['contents'][0]['parts'][0]['text'];

            return str_contains($teks, 'Pentas Gandrung') && str_contains($teks, 'Prestasi');
        });
    }

    public function test_ai_001_langkah5_file_gif_ditolak(): void
    {
        Http::fake();

        $res = $this->controller->generateAiBerita(
            $this->requestAi([], [UploadedFile::fake()->image('file.gif', 100, 100)])
        );

        $this->assertSame(422, $res->getStatusCode());
        $this->assertFalse($res->getData(true)['success']);
        Http::assertNothingSent();
    }

    #[Group('selisih-spesifikasi')]
    public function test_ai_001_langkah5_pesan_penolakan_gif_sesuai_test_case(): void
    {
        Http::fake();

        $res = $this->controller->generateAiBerita(
            $this->requestAi([], [UploadedFile::fake()->image('file.gif', 100, 100)])
        );

        $this->assertStringContainsString('Format atau ukuran foto tidak sesuai', $res->getData(true)['message']);
    }

    #[Group('selisih-spesifikasi')]
    public function test_ai_001_langkah4_tanpa_foto_ditolak(): void
    {
        // CATATAN: rule 'foto.*' bersifat nullable dan tidak ada pengecekan foto wajib,
        // sehingga request tanpa foto tetap diteruskan ke Gemini.
        Http::fake(['generativelanguage.googleapis.com/*' => Http::response($this->bodyGemini('<p>x</p>'), 200)]);

        $res = $this->controller->generateAiBerita($this->requestAi());

        $this->assertSame(422, $res->getStatusCode());
        $this->assertStringContainsString('Foto berita wajib diunggah', $res->getData(true)['message']);
        Http::assertNothingSent();
    }
}
