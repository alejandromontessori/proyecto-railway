<?php

namespace Database\Seeders;

use App\Models\Comentario;
use App\Models\User;
use App\Models\Receta;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();
        Receta::factory(5)->create();
        foreach ($users as $user) {
            $comentarios = Comentario::factory(5)->create();
            foreach ($comentarios as $comentario) {
                $comentario->id_autor = $user->id;
                $comentario->id_receta = fake()->numberBetween(1, 5);
                $comentario->save();

            }
            $comentarios = Comentario::factory(20)->create();
            foreach ($comentarios as $comentario) {
                $comentario->id_autor = fake()->numberBetween(1, 10);
                $comentarioRespondido = fake()->numberBetween(1, 50);
                $comentario->id_respondido = $comentarioRespondido;
                $comentario->id_receta = Comentario::find($comentarioRespondido)->id_receta;
                $comentario->save();
            }
        }
    }
}
