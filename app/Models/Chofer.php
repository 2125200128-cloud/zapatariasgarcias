<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chofer extends Model
{
    //

    protected $fillable = [
        'nombre',
        'apellido',
        'contacto',
        'imagen',
        'estatus'
    ];

        public function trayectos()
    {
        return $this->hasMany(Trayecto::class);
    }
}
