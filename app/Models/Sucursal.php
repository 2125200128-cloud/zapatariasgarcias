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

    // La matriz es una sucursal más (mismo nombre que ya usa
    // config('ubicaciones.matriz') para el mapa de flota); se ubica por
    // nombre en vez de un id fijo o una columna nueva.
    public static function matriz()
    {
        return static::where('nombre', config('ubicaciones.matriz.nombre'))->first();
    }
}
