<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Empleado extends Authenticatable
{
    use Notifiable;

     protected $fillable =[

        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'telefono',
        'contrasena',
        'usuario',
        'rol',
        'estatus',
        'calle',
        'numero',
        'municipio',
        'codigo_postal',
        'imagen'

    ];


    protected $hidden = [
        'contrasena',
    ];

    public $timestamps = false;

    // La columna real es 'contrasena' (sin ñ), no 'password' — Laravel siempre
    // busca el password autenticable a través de este método, así que aquí se
    // hace el mapeo.
    public function getAuthPassword()
    {
        return $this->contrasena;
    }

    public function sucursales(){
        return $this->hasMany(Sucursal::class);
    }

        public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    public function esAdministrador(): bool
    {
        return $this->rol === 'Administrador';
    }

    // La sucursal que este empleado encabeza (si es encargado de alguna).
    public function miSucursal()
    {
        return $this->sucursales->first();
    }

    public function esMatriz(): bool
    {
        $sucursal = $this->miSucursal();
        return $sucursal && $sucursal->id === optional(Sucursal::matriz())->id;
    }

}
