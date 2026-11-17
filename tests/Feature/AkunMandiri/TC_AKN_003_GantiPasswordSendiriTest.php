<?php

namespace Tests\Feature\AkunMandiri;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * TC-AKN-003 : Pengguna dapat mengganti password akun sendiri dengan
 * password lama yang cocok dan password baru minimal 8 karakter.
 */
class TC_AKN_003_GantiPasswordSendiriTest extends TestCase
{
    use RefreshDatabase;

    public function test_akn_003_password_baru_tersimpan(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }
}
