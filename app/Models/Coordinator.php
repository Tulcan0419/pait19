<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Importante: Extender Authenticatable
use Illuminate\Notifications\Notifiable;

class Coordinator extends Authenticatable // El modelo debe extender Authenticatable
{
    use HasFactory, Notifiable;

    // Define el guardia de autenticación para este modelo.
    // Esto le dice a Laravel qué configuración de guardia usar para los coordinadores.
    protected $guard = 'coordinador';

    /**
     * Los atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo',
    ];

    /**
     * Los atributos que deben ocultarse para la serialización.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser casteados.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // 'email_verified_at' => 'datetime', // Puedes añadir esto si manejas verificación de email
        'password' => 'hashed', // Asegura que la contraseña se hashee automáticamente al guardarla
    ];
}