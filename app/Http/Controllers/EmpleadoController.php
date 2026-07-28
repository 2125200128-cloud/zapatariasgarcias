<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use Illuminate\Database\Eloquent\Relations\HasMany;
class EmpleadoController extends Controller
{
    //
   public function inicio()
    {
   
        return view('empleado/inicio' );
    }

        public function listado()
    {
    //  $carros = Carro::all();

        // return view('carro/lista', compact('carro'));
    return view('chofer/lista');
    }

    public function formulario(){

    return view ('chofer/formulario');
    }
  
    
    public function guardar(){

    return redirect('/chofer')->with('success', 'Chofer guardado exitosamente.');
    }

    public function editar(Request $request){
    //    $id = $request->route('id');
    //    $carro = Carro::find($id);
    //     if (!$carro) {
    //         return redirect('/carro')->with('error', 'Carro no encontrado');
    //     }
    //     return view('carro/edicion', ['carro' => $carro]);

    return redirect('/chofer')->with('success', 'Chofer actualizado correctamente.');
    }

    public function eliminar(){

    return redirect('/chofer')->with('success', 'Chofer eliminado');
    }


    public function mostrar(){
        return view();
    }
}
