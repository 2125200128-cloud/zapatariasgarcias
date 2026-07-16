<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trayecto extends Model
{
    //
    protected $fillable = [
        'chofer_id',
        'carro_id',
        'pedido_id',
        'fecha_envio',
        'descripcion',
        'estatus'
    ];

    public function chofer()
    {
        return $this->belongsTo(Chofer::class);
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function carro()
    {
        return $this->belongsTo(Carro::class);
    }
}
