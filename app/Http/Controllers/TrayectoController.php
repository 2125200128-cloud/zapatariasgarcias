<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrayectoController extends Controller
{
    //
    public function inicio()
    {
        return view('trayecto/inicio');
    }

    
    public function listado()
    {
    //  $carros = Carro::all();

        // return view('carro/lista', compact('carro'));
    return view('trayecto/lista');
    }

    public function formulario(){

    return view ('trayecto/formulario');
    }
  
    
    public function guardar(){

    return redirect('/trayecto')->with('success', 'Trayecto guardado exitosamente.');
    }

    public function editar(Request $request){
    //    $id = $request->route('id');
    //    $carro = Carro::find($id);
    //     if (!$carro) {
    //         return redirect('/carro')->with('error', 'Carro no encontrado');
    //     }
    //     return view('carro/edicion', ['carro' => $carro]);

    return redirect('/trayecto')->with('success', 'Trayecto actualizada correctamente.');
    }

    public function eliminar(){

    return redirect('/trayecto')->with('success', 'Trayecto eliminada');
    }

    public function mostrar(){
        return view();
    }
}
