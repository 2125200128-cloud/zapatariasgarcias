<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Chofer;
use Illuminate\Hashing\AbstractHasher\hasMany;

class Carro extends Model
{
    //
      protected $fillable =[
        'placas',
        'marca',
        'color',
        'capacidad',
        'imagen',
        'dimensiones',
        'estatus'
    ];
public $timestamps = false;

    public function trayectos()
    {
        return $this->hasMany(Trayecto::class);
    }
}
