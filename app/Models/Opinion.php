<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Opinion extends Model
{
    use HasFactory;

    protected $table = 'opiniones';

    protected $fillable = [
        'texto',
        'valoracion',
        'id_idea',
        'id_autor',
        'id_respondido',
    ];

    protected $casts = [
        'valoracion' => 'integer',
    ];

    /** Autor de la opinión (users.id) */
    public function autor(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'id_autor', 'id');
    }

    /** Idea a la que pertenece la opinión (ideas.id) */
    public function idea(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Idea::class, 'id_idea', 'id');
    }

    /** Opinión padre (si responde a otra) — nombre esperado en controladores/vistas */
    public function responde(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Opinion::class, 'id_respondido');
    }

    /** Respuestas hijas de esta opinión */
    public function respuestas(): HasMany
    {
        return $this->hasMany(\App\Models\Opinion::class, 'id_respondido');
    }
}
