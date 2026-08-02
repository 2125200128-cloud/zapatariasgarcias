<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carro;

class CarroController extends Controller
{
    public function inicio()
    {
        $carros = Carro::with(['trayectos' => function ($query) {
            $query->whereNotIn('estatus', ['Entregado', 'Cancelado'])->with('chofer');
        }])->get();

        return view('carro/inicio', compact('carros'));
    }

    public function formulario()
    {
        return view('carro/formulario');
    }

    public function editar(Request $request)
    {
        $id = $request->route('id');
        $carro = Carro::find($id);
        if (!$carro) {
            return redirect('/carro')->with('error', 'Carro no encontrado');
        }
        return view('carro/edicion', ['carro' => $carro]);
    }

    public function actualizar(Request $request)
    {
        $id = $request->route('id');
        $carro = Carro::find($id);
        if (!$carro) {
            return redirect('/carro')->with('error', 'Carro no encontrado');
        }
        $carro->placas = $request->input('placas');
        $carro->marca = $request->input('marca');
        $carro->color = $request->input('color');
        $carro->capacidad = $request->input('capacidad');
        $carro->dimenciones = $request->input('dimenciones');
        $carro->estatus = $request->input('estatus');
        $carro->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'carro_' . $carro->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/carros', $nombre, 'public');
            $carro->imagen = url('storage/' . $ruta);
            $carro->save();
        }

        return redirect('/carro')->with('success', 'Carro actualizado');
    }

    public function guardar(Request $request)
    {
        $carro = new Carro();
        $carro->placas = $request->input('placas');
        $carro->marca = $request->input('marca');
        $carro->color = $request->input('color');
        $carro->capacidad = $request->input('capacidad');
        $carro->dimenciones = $request->input('dimenciones');
        $carro->estatus = $request->input('estatus');
        $carro->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'carro_' . $carro->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/carros', $nombre, 'public');
            $carro->imagen = url('storage/' . $ruta);
            $carro->save();
        }

        return redirect('/carro')->with('success', 'Carro guardado exitosamente.');
    }

    public function eliminar(Request $request)
    {
        $id = $request->route('id');
        $carro = Carro::find($id);
        if (!$carro) {
            return redirect('/carro')->with('error', 'Carro no encontrado');
        }
        $carro->delete();
        return redirect('/carro')->with('success', 'Carro eliminado');
    }

    public function mostrar(Request $request)
    {
        $id = $request->route('id');
        $carro = Carro::find($id);
        if (!$carro) {
            return redirect('/carro')->with('error', 'Carro no encontrado');
        }
        return view('carro/borrado', ['carro' => $carro]);
    }
}
