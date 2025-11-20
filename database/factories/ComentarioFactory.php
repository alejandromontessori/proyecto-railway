<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comentario>
 */
class ComentarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "id_autor" => 1,
            "id_receta" => 1,
            "id_respondido" => null,
            "texto" => fake()->text,
            "valoracion" => fake()->numberBetween(1,5)
        ];
    }
}
