<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RecetaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => (fake()->word()." de ".fake()->country()),
            'descripcion' => fake()->paragraph(),
            'updated_at' => now(),
            'created_at' => fake()->date(),
            'id_autor' => 1,
            'dificultad' => fake()->randomElement(["facil","media","dificil"]),
        ];
    }
}
