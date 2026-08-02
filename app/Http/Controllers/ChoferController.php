<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chofer;

class ChoferController extends Controller
{
    public function inicio()
    {
        $choferes = Chofer::with(['trayectos' => function ($query) {
            $query->whereNotIn('estatus', ['Entregado', 'Cancelado'])->with('carro');
        }])->get();

        return view('chofer/inicio', compact('choferes'));
    }

    public function formulario()
    {
        return view('chofer/formulario');
    }

    public function editar(Request $request)
    {
        $id = $request->route('id');
        $chofer = Chofer::find($id);
        if (!$chofer) {
            return redirect('/chofer')->with('error', 'Chofer no encontrado');
        }
        return view('chofer/edicion', ['chofer' => $chofer]);
    }

    public function actualizar(Request $request)
    {
        $id = $request->route('id');
        $chofer = Chofer::find($id);
        if (!$chofer) {
            return redirect('/chofer')->with('error', 'Chofer no encontrado');
        }
        $chofer->nombre = $request->input('nombre');
        $chofer->apellido = $request->input('apellido');
        $chofer->contacto = $request->input('contacto');
        $chofer->estatus = $request->input('estatus');
        $chofer->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'chofer_' . $chofer->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/choferes', $nombre, 'public');
            $chofer->imagen = url('storage/' . $ruta);
            $chofer->save();
        }

        return redirect('/chofer')->with('success', 'Chofer actualizado');
    }

    public function guardar(Request $request)
    {
        $chofer = new Chofer();
        $chofer->nombre = $request->input('nombre');
        $chofer->apellido = $request->input('apellido');
        $chofer->contacto = $request->input('contacto');
        $chofer->estatus = $request->input('estatus');
        $chofer->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'chofer_' . $chofer->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/choferes', $nombre, 'public');
            $chofer->imagen = url('storage/' . $ruta);
            $chofer->save();
        }

        return redirect('/chofer')->with('success', 'Chofer guardado exitosamente.');
    }

    public function eliminar(Request $request)
    {
        $id = $request->route('id');
        $chofer = Chofer::find($id);
        if (!$chofer) {
            return redirect('/chofer')->with('error', 'Chofer no encontrado');
        }
        $chofer->estatus = 'Inactivo';
        $chofer->save();
        return redirect('/chofer')->with('success', 'Chofer eliminado');
    }

    public function mostrar(Request $request)
    {
        $id = $request->route('id');
        $chofer = Chofer::find($id);
        if (!$chofer) {
            return redirect('/chofer')->with('error', 'Chofer no encontrado');
        }
        return view('chofer/borrado', ['chofer' => $chofer]);
    }
}
