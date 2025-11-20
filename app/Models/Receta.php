<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Receta extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'id_autor',
        'valoracion',
        'fotoReceta',
    ];
    public function autor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class,"id_autor", "id");
    }

    public function comentarios() : HasMany
    {
        return $this->hasMany(Comentario::class, 'id_receta');
    }

    public function ingredientes() : BelongsToMany{

        return $this->belongsToMany(Ingrediente::class,'receta-ingrediente','id_receta','id_ingrediente');

    }

    public function favoritos()
    {
        return $this->hasMany(Favorito::class, 'id_receta');
    }
}
