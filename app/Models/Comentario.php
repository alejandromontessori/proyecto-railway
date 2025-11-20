<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = [
        'texto',
        'id_autor',
        'id_receta',
        'id_respondido', // si también usas respuestas
        'valoracion',
    ];
    public function responde() : BelongsTo
    {
       return $this->belongsTo(Comentario::class, 'id_respondido');
    }
    public function respuestas() : HasMany
    {
        return $this->hasMany(Comentario::class, 'id_respondido');
    }
    public function autor() : BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_autor');
    }
    public function receta() : BelongsTo
    {
        return $this->belongsTo(Receta::class, 'id_receta');
    }
}
