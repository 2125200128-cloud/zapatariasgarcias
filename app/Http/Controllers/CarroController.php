<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarroController extends Controller
{
    //
    public function inicio()
    {
        return view('carro/inicio');
    }

    public function listado()
    {
    //  $carros = Carro::all();

        // return view('carro/lista', compact('carro'));
    return view('carro/lista');
    }

    public function formulario(){

    return view ('carro/formulario');
    }
  
    
    public function guardar(){

    return redirect('/carro')->with('success', 'Carro guardado exitosamente.');
    }

    public function editar(Request $request){
    //    $id = $request->route('id');
    //    $carro = Carro::find($id);
    //     if (!$carro) {
    //         return redirect('/carro')->with('error', 'Carro no encontrado');
    //     }
    //     return view('carro/edicion', ['carro' => $carro]);

    return redirect('/carro')->with('success', 'Carro actualizado correctamente.');
    }

    public function eliminar(){

    return redirect('/carro')->with('success', 'Carro eliminado');
    }

    public function mostrar(){
        return view();
    }
}
