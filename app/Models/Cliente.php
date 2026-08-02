<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{

    protected $fillable =[
        
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'telefono',
        'contrasena',
        'usuario',
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

    
}
