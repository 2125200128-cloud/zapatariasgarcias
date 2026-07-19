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

    // $empleados = Empleado::count();
    // $clientes = Cliente::count();
    // $productos = Producto::count();
    // $pedidos = Pedido::count();

    // return view('inicio', compact(
    //     'empleados',
    //     'clientes',
    //     'productos',
    //     'pedidos'
    // ));
         return view('/inicio');
    }
}
