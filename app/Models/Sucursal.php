<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    protected $table = 'sucursales';

    protected $fillable = [
        'empleado_id',
        'nombre',
        'calle',
        'numero',
        'municipio',
        'codigo_postal',
        'contacto',
        'imagen',
        'estatus'
    ];

    public $timestamps = false;



        public function empleado()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function inventarios()
    {
        return $this->hasMany(Inventario::class);
    }
}
