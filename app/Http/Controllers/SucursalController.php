<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SucursalController extends Controller
{
    //
    public function inicio()
    {
        return view('sucursal/inicio');
    }

    
    public function listado()
    {
    //  $carros = Carro::all();

        // return view('carro/lista', compact('carro'));
    return view('sucursal/lista');
    }

    public function formulario(){

    return view ('sucursal/formulario');
    }
  
    
    public function guardar(){

    return redirect('/sucursal')->with('success', 'Sucursal guardado exitosamente.');
    }

    public function editar(Request $request){
    //    $id = $request->route('id');
    //    $carro = Carro::find($id);
    //     if (!$carro) {
    //         return redirect('/carro')->with('error', 'Carro no encontrado');
    //     }
    //     return view('carro/edicion', ['carro' => $carro]);

    return redirect('/sucursal')->with('success', 'Sucursal actualizada correctamente.');
    }

    public function eliminar(){

    return redirect('/sucursal')->with('success', 'Sucursal eliminada');
    }

    public function mostrar(){
        return view();
    }
}
