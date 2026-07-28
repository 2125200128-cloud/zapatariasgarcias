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

    public function listado()
    {
    //  $carros = Carro::all();

        // return view('carro/lista', compact('carro'));
    return view('producto/lista');
    }

    public function formulario(){

    return view ('producto/formulario');
    }
  
    
    public function guardar(){

    return redirect('/producto')->with('success', 'Producto guardado exitosamente.');
    }

    public function editar(Request $request){
    //    $id = $request->route('id');
    //    $carro = Carro::find($id);
    //     if (!$carro) {
    //         return redirect('/carro')->with('error', 'Carro no encontrado');
    //     }
    //     return view('carro/edicion', ['carro' => $carro]);

    return redirect('/producto')->with('success', 'Producto actualizado correctamente.');
    }

    public function eliminar(){

    return redirect('/producto')->with('success', 'Producto eliminado');
    }

    public function mostrar(){
        return view();
    }
    }

