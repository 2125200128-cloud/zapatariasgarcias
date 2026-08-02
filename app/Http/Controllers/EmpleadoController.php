<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Empleado;

class EmpleadoController extends Controller
{
    public function inicio()
    {
        $empleados = Empleado::all();

        return view('empleado/inicio', compact('empleados'));
    }

    public function formulario()
    {
        return view('empleado/formulario');
    }

    public function editar(Request $request)
    {
        $id = $request->route('id');
        $empleado = Empleado::find($id);
        if (!$empleado) {
            return redirect('/empleado')->with('error', 'Empleado no encontrado');
        }
        return view('empleado/edicion', ['empleado' => $empleado]);
    }

    public function actualizar(Request $request)
    {
        $id = $request->route('id');
        $empleado = Empleado::find($id);
        if (!$empleado) {
            return redirect('/empleado')->with('error', 'Empleado no encontrado');
        }
        $empleado->nombre = $request->input('nombre');
        $empleado->apellido_paterno = $request->input('apellido_paterno');
        $empleado->apellido_materno = $request->input('apellido_materno');
        $empleado->telefono = $request->input('telefono');
        $empleado->correo = $request->input('correo');
        $empleado->usuario = $request->input('usuario');
        if ($request->filled('contrasena')) {
            $empleado->contrasena = Hash::make($request->input('contrasena'));
        }
        $empleado->rol = $request->input('rol');
        $empleado->estatus = $request->input('estatus');
        $empleado->calle = $request->input('calle');
        $empleado->numero = $request->input('numero');
        $empleado->municipio = $request->input('municipio');
        $empleado->codigo_postal = $request->input('codigo_postal');
        $empleado->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'empleado_' . $empleado->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/empleados', $nombre, 'public');
            $empleado->imagen = url('storage/' . $ruta);
            $empleado->save();
        }

        return redirect('/empleado')->with('success', 'Empleado actualizado');
    }

    public function guardar(Request $request)
    {
        $empleado = new Empleado();
        $empleado->nombre = $request->input('nombre');
        $empleado->apellido_paterno = $request->input('apellido_paterno');
        $empleado->apellido_materno = $request->input('apellido_materno');
        $empleado->telefono = $request->input('telefono');
        $empleado->correo = $request->input('correo');
        $empleado->usuario = $request->input('usuario');
        $empleado->contrasena = Hash::make($request->input('contrasena'));
        $empleado->rol = $request->input('rol');
        $empleado->estatus = $request->input('estatus');
        $empleado->calle = $request->input('calle');
        $empleado->numero = $request->input('numero');
        $empleado->municipio = $request->input('municipio');
        $empleado->codigo_postal = $request->input('codigo_postal');
        $empleado->imagen = 'sin-imagen.jpg';
        $empleado->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'empleado_' . $empleado->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/empleados', $nombre, 'public');
            $empleado->imagen = url('storage/' . $ruta);
            $empleado->save();
        }

        return redirect('/empleado')->with('success', 'Empleado guardado exitosamente.');
    }

    public function eliminar(Request $request)
    {
        $id = $request->route('id');
        $empleado = Empleado::find($id);
        if (!$empleado) {
            return redirect('/empleado')->with('error', 'Empleado no encontrado');
        }
        $empleado->estatus = 'Inactivo';
        $empleado->save();
        return redirect('/empleado')->with('success', 'Empleado eliminado');
    }

    public function mostrar(Request $request)
    {
        $id = $request->route('id');
        $empleado = Empleado::find($id);
        if (!$empleado) {
            return redirect('/empleado')->with('error', 'Empleado no encontrado');
        }
        return view('empleado/borrado', ['empleado' => $empleado]);
    }
}
