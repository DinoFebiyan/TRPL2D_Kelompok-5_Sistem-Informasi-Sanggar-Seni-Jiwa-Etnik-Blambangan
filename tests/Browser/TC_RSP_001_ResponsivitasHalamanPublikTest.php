<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TC_RSP_001_ResponsivitasHalamanPublikTest extends DuskTestCase
{
    /**
     * @dataProvider halamanPublikProvider
     */
    public function test_rsp_001_halaman_publik_responsif_tiga_viewport(string $url): void
    {
        $this->browse(function (Browser $browser) use ($url) {
            foreach ([360, 768, 1366] as $lebar) {
                $tinggi = $lebar === 1366 ? 768 : 1024;

                $browser->resize($lebar, $tinggi)
                    ->visit($url)
                    ->assertScript('document.documentElement.scrollWidth <= window.innerWidth');
            }
        });
    }

    public static function halamanPublikProvider(): array
    {
        return [
            ['url' => '/'],
            ['url' => '/event'],
            ['url' => '/berita'],
            ['url' => '/katalog'],
            ['url' => '/galeri'],
            ['url' => '/profil-sanggar'],
        ];
    }
}
