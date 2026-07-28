<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    //
    public function inicio()
    {
        $proveedores = Proveedor::all();

        return view('proveedor/inicio', compact('proveedores'));
    }

    public function formulario()
    {
        return view('proveedor/formulario');
    }

    public function editar(Request $request)
    {
        $id = $request->route('id');
        $proveedor = Proveedor::find($id);
        if (!$proveedor) {
            return redirect('/proveedor')->with('error', 'Proveedor no encontrado');
        }
        return view('proveedor/edicion', ['proveedor' => $proveedor]);
    }

    public function actualizar(Request $request)
    {
        $id = $request->route('id');
        $proveedor = Proveedor::find($id);
        if (!$proveedor) {
            return redirect('/proveedor')->with('error', 'Proveedor no encontrado');
        }
        $proveedor->nombre = $request->input('nombre');
        $proveedor->contacto = $request->input('contacto');
        $proveedor->correo = $request->input('correo');
        $proveedor->calle = $request->input('calle');
        $proveedor->numero = $request->input('numero');
        $proveedor->municipio = $request->input('municipio');
        $proveedor->codigo_postal = $request->input('codigo_postal');
        $proveedor->estatus = $request->input('estatus');
        $proveedor->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'proveedor_' . $proveedor->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/proveedores', $nombre, 'public');
            $proveedor->imagen = url('storage/' . $ruta);
            $proveedor->save();
        }

        return redirect('/proveedor')->with('success', 'Proveedor actualizado');
    }

    public function guardar(Request $request)
    {
        $proveedor = new Proveedor();
        $proveedor->nombre = $request->input('nombre');
        $proveedor->contacto = $request->input('contacto');
        $proveedor->correo = $request->input('correo');
        $proveedor->calle = $request->input('calle');
        $proveedor->numero = $request->input('numero');
        $proveedor->municipio = $request->input('municipio');
        $proveedor->codigo_postal = $request->input('codigo_postal');
        $proveedor->estatus = $request->input('estatus');
        $proveedor->imagen = 'sin-imagen.jpg';
        $proveedor->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'proveedor_' . $proveedor->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/proveedores', $nombre, 'public');
            $proveedor->imagen = url('storage/' . $ruta);
            $proveedor->save();
        }

        return redirect('/proveedor')->with('success', 'Proveedor guardado exitosamente.');
    }

    public function eliminar(Request $request)
    {
        $id = $request->route('id');
        $proveedor = Proveedor::find($id);
        if (!$proveedor) {
            return redirect('/proveedor')->with('error', 'Proveedor no encontrado');
        }
        $proveedor->estatus = 'Inactivo';
        $proveedor->save();
        return redirect('/proveedor')->with('success', 'Proveedor eliminado');
    }

    public function mostrar(Request $request)
    {
        $id = $request->route('id');
        $proveedor = Proveedor::find($id);
        if (!$proveedor) {
            return redirect('/proveedor')->with('error', 'Proveedor no encontrado');
        }
        return view('proveedor/borrado', ['proveedor' => $proveedor]);
    }
}
