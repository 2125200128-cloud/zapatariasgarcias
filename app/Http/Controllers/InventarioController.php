<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventarioController extends Controller
{
    //
    public function inicio()
    {
        return view('inventario/inicio');
    }

    
    public function listado()
    {
    //  $carros = Carro::all();

        // return view('carro/lista', compact('carro'));
    return view('inventario/lista');
    }

    public function formulario(){

    return view ('inventario/formulario');
    }
  
    
    public function guardar(){

    return redirect('/inventario')->with('success', 'Inventario guardado exitosamente.');
    }

    public function editar(Request $request){
    //    $id = $request->route('id');
    //    $carro = Carro::find($id);
    //     if (!$carro) {
    //         return redirect('/carro')->with('error', 'Carro no encontrado');
    //     }
    //     return view('carro/edicion', ['carro' => $carro]);

    return redirect('/inventario')->with('success', 'Inventario actualizado correctamente.');
    }

    public function eliminar(){

    return redirect('/inventario')->with('success', 'Inventario eliminado');
    }

    public function mostrar(){
        return view();
    }
}
