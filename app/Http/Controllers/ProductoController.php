<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductoController extends Controller
{
    //
    public function inicio()
    {
        return view('producto/inicio');
    }

    public function formulario(){
        return view('producto/formulario');
    }

    public function editar(){
        return view('producto/editar');
    }
}
