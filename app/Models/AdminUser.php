<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminUser extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'correo',
        'contrasena',
        'rol',
        'total_reportes',
        'reputacion_usuario'
    ];

    protected $hidden = [
        'contrasena',
        'remember_token',
    ];


    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function getAuthIdentifierName()
    {
        return 'correo';
    }

    public function isAdmin(): bool
    {
        return $this->rol === 'admin';
    }
}
