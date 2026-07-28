<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PedidoController extends Controller
{
    //
    public function inicio()
    {
        return view('pedido/inicio');
    }

    
    public function listado()
    {
    //  $carros = Carro::all();

        // return view('carro/lista', compact('carro'));
    return view('marca/lista');
    }

    public function formulario(){

    return view ('marca/formulario');
    }
  
    
    public function guardar(){

    return redirect('/marca')->with('success', 'Marca guardado exitosamente.');
    }

    public function editar(Request $request){
    //    $id = $request->route('id');
    //    $carro = Carro::find($id);
    //     if (!$carro) {
    //         return redirect('/carro')->with('error', 'Carro no encontrado');
    //     }
    //     return view('carro/edicion', ['carro' => $carro]);

    return redirect('/marca')->with('success', 'Marca actualizado correctamente.');
    }

    public function eliminar(){

    return redirect('/marca')->with('success', 'Marca eliminada');
    }

    public function mostrar(){
        return view();
    }
}
