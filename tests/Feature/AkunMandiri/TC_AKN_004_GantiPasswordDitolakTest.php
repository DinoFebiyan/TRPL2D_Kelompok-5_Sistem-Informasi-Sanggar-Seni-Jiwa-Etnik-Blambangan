<?php

namespace Tests\Feature\AkunMandiri;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * TC-AKN-004 : Penggantian password ditolak ketika
 * password lama tidak cocok.
 */
class TC_AKN_004_GantiPasswordDitolakTest extends TestCase
{
    use RefreshDatabase;

    public function test_akn_004_password_lama_salah_ditolak(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('updatePassword', 'current_password')
            ->assertRedirect('/profile');
    }
}
