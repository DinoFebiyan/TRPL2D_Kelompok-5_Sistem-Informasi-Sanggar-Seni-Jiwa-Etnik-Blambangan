<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'deskripsi' => fake()->text(),
            'tanggal' => fake()->date(),
            'jam' => fake()->time(),
            'lokasi' => fake()->address(),
            'kategori' => fake()->randomElement(['Pelatihan', 'Pertunjukan', 'Latihan']),
            'status' => fake()->randomElement(['belum selesai', 'selesai']),
            'user_id' => 1,
        ];
    }
}
