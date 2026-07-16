<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    //


    protected $fillable = [
        'nombre',
        'contacto',
        'correo',
        'calle',
        'estatus',
        'municipio',
        'codigo_postal',
        'imagen'
    ];

        public function marcas()
    {
        return $this->hasMany(Marca::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
