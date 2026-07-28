<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    //
    public function inicio()
    {
        return view('proveedor/inicio');
    }

    
    public function listado()
    {
    //  $carros = Carro::all();

        // return view('carro/lista', compact('carro'));
    return view('proveedor/lista');
    }

    public function formulario(){

    return view ('proveedor/formulario');
    }
  
    
    public function guardar(){

    return redirect('/proveedor')->with('success', 'Proveedor guardado exitosamente.');
    }

    public function editar(Request $request){
    //    $id = $request->route('id');
    //    $carro = Carro::find($id);
    //     if (!$carro) {
    //         return redirect('/carro')->with('error', 'Carro no encontrado');
    //     }
    //     return view('carro/edicion', ['carro' => $carro]);

    return redirect('/proveedor')->with('success', 'Proveedor actualizado correctamente.');
    }

    public function eliminar(){

    return redirect('/proveedor')->with('success', 'proveedor eliminado');
    }

    public function mostrar(){
        return view();
    }
}
