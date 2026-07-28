<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sucursal;
use App\Models\Empleado;

class SucursalController extends Controller
{
    //
    public function inicio()
    {
        $sucursales = Sucursal::with('empleado')->get();

        return view('sucursal/inicio', compact('sucursales'));
    }

    public function formulario()
    {
        $empleados = Empleado::all();

        return view('sucursal/formulario', compact('empleados'));
    }

    public function editar(Request $request)
    {
        $id = $request->route('id');
        $sucursal = Sucursal::find($id);
        if (!$sucursal) {
            return redirect('/sucursal')->with('error', 'Sucursal no encontrada');
        }
        $empleados = Empleado::all();

        return view('sucursal/edicion', compact('sucursal', 'empleados'));
    }

    public function actualizar(Request $request)
    {
        $id = $request->route('id');
        $sucursal = Sucursal::find($id);
        if (!$sucursal) {
            return redirect('/sucursal')->with('error', 'Sucursal no encontrada');
        }
        $sucursal->nombre = $request->input('nombre');
        $sucursal->empleado_id = $request->input('empleado_id');
        $sucursal->calle = $request->input('calle');
        $sucursal->numero = $request->input('numero');
        $sucursal->municipio = $request->input('municipio');
        $sucursal->codigo_postal = $request->input('codigo_postal');
        $sucursal->contacto = $request->input('contacto');
        $sucursal->estatus = $request->input('estatus');
        $sucursal->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'sucursal_' . $sucursal->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/sucursales', $nombre, 'public');
            $sucursal->imagen = url('storage/' . $ruta);
            $sucursal->save();
        }

        return redirect('/sucursal')->with('success', 'Sucursal actualizada');
    }

    public function guardar(Request $request)
    {
        $sucursal = new Sucursal();
        $sucursal->nombre = $request->input('nombre');
        $sucursal->empleado_id = $request->input('empleado_id');
        $sucursal->calle = $request->input('calle');
        $sucursal->numero = $request->input('numero');
        $sucursal->municipio = $request->input('municipio');
        $sucursal->codigo_postal = $request->input('codigo_postal');
        $sucursal->contacto = $request->input('contacto');
        $sucursal->estatus = $request->input('estatus');
        $sucursal->imagen = 'sin-imagen.jpg';
        $sucursal->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'sucursal_' . $sucursal->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/sucursales', $nombre, 'public');
            $sucursal->imagen = url('storage/' . $ruta);
            $sucursal->save();
        }

        return redirect('/sucursal')->with('success', 'Sucursal guardada exitosamente.');
    }

    public function eliminar(Request $request)
    {
        $id = $request->route('id');
        $sucursal = Sucursal::find($id);
        if (!$sucursal) {
            return redirect('/sucursal')->with('error', 'Sucursal no encontrada');
        }
        $sucursal->estatus = 'Inactivo';
        $sucursal->save();
        return redirect('/sucursal')->with('success', 'Sucursal eliminada');
    }

    public function mostrar(Request $request)
    {
        $id = $request->route('id');
        $sucursal = Sucursal::find($id);
        if (!$sucursal) {
            return redirect('/sucursal')->with('error', 'Sucursal no encontrada');
        }
        return view('sucursal/borrado', ['sucursal' => $sucursal]);
    }
}
