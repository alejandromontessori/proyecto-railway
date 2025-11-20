<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingrediente extends Model
{
    public function recetas() : BelongsToMany{

        return $this->belongsToMany(Receta::class,'receta-ingrediente','id_ingrediente','id_receta');

    }
}
