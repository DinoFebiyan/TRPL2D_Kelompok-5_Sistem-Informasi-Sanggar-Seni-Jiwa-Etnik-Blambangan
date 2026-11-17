<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class TC_RSP_002_ResponsivitasHalamanAdminTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * @dataProvider halamanAdminProvider
     */
    public function test_rsp_002_halaman_admin_responsif_tiga_viewport(string $url): void
    {
        $superadmin = User::factory()->create(['peran' => 'superadmin']);

        $this->browse(function (Browser $browser) use ($superadmin, $url) {
            foreach ([360, 768, 1366] as $lebar) {
                $tinggi = $lebar === 1366 ? 768 : 1024;

                $browser->resize($lebar, $tinggi)
                    ->loginAs($superadmin->id)
                    ->visit($url)
                    ->assertPathIs($url)
                    ->assertScript('document.documentElement.scrollWidth <= window.innerWidth');
            }
        });
    }

    public static function halamanAdminProvider(): array
    {
        return [
            ['url' => '/admin/dashboard'],
            ['url' => '/admin/kelola-event'],
            ['url' => '/admin/kelola-berita'],
        ];
    }
}
