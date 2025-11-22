<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // importante para Auth
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'mysql';

    /**
     * Campos asignables en masa
     */
    protected $fillable = [
        'apodo',
        'nombre',
        'apellidos',
        'email',
        'password',
        'rol',          // Visitante | Autor | Administrador
        'foto_perfil',
        'email_verified_at',
        'remember_token',
    ];

    /**
     * Ocultos para arrays/JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts de atributos
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Helpers de rol
     */
    public function esAdmin(): bool
    {
        return $this->rol === 'Administrador';
    }

    public function esAutor(): bool
    {
        return $this->rol === 'Autor' || $this->rol === 'Administrador';
    }

    /**
     * Relación: ideas creadas por este usuario (id_autor en ideas)
     */
    public function ideas()
    {
        return $this->hasMany(\App\Models\Idea::class, 'id_autor', 'id');
    }

    public function opiniones()
    {
        return $this->hasMany(\App\Models\Opinion::class, 'id_autor', 'id');
    }
}
