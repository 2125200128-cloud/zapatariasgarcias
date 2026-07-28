<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Pedido;



class InicioController extends Controller
{
    //
    public function inicio()
    {


    
            return view('/inicio');
    }
}
