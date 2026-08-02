<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
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

    // Reglas compartidas por guardar()/actualizar(). 'contacto' es un
    // teléfono a 10 dígitos (así está en la BD, columna varchar(10)); sin
    // esta validación, un nombre de contacto o un teléfono con guiones
    // truena la app con un error de MySQL sin control.
    private function reglas($idProveedor = null)
    {
        return [
            'nombre' => 'required|string|max:100',
            'contacto' => 'required|digits:10',
            'correo' => ['required', 'email', 'max:150', Rule::unique('proveedores', 'correo')->ignore($idProveedor)],
            'calle' => 'required|string|max:255',
            'numero' => 'required|integer',
            'municipio' => 'required|string|max:100',
            'codigo_postal' => 'required|string|max:5',
            'estatus' => 'required|in:Activo,Inactivo',
        ];
    }

    private function mensajes()
    {
        return [
            'contacto.digits' => 'El contacto debe ser un teléfono a 10 dígitos, sin espacios ni guiones.',
            'correo.unique' => 'Ya existe un proveedor registrado con ese correo.',
        ];
    }

    public function actualizar(Request $request)
    {
        $id = $request->route('id');
        $proveedor = Proveedor::find($id);
        if (!$proveedor) {
            return redirect('/proveedor')->with('error', 'Proveedor no encontrado');
        }

        $request->validate($this->reglas($id), $this->mensajes());

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
        $request->validate($this->reglas(), $this->mensajes());

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
