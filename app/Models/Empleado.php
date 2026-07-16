<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    //
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


    public function sucursales(){
        return $this->hasMany(Sucursal::class);
    }

        public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

}
