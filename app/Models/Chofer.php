<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chofer extends Model
{
    //
    protected $table = 'choferes';

    protected $fillable = [
        'nombre',
        'apellido',
        'contacto',
        'imagen',
        'estatus'
    ];

    public $timestamps = false;

        public function trayectos()
    {
        return $this->hasMany(Trayecto::class);
    }
}
