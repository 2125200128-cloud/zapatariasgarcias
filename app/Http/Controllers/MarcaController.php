<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Marca;
use App\Models\Proveedor;

class MarcaController extends Controller
{
    public function inicio()
    {
        $marcas = Marca::with('proveedor')->get();

        return view('marca/inicio', compact('marcas'));
    }

    public function formulario()
    {
        $proveedores = Proveedor::all();

        return view('marca/formulario', compact('proveedores'));
    }

    public function editar(Request $request)
    {
        $id = $request->route('id');
        $marca = Marca::find($id);
        if (!$marca) {
            return redirect('/marca')->with('error', 'Marca no encontrada');
        }
        $proveedores = Proveedor::all();

        return view('marca/edicion', compact('marca', 'proveedores'));
    }

    public function actualizar(Request $request)
    {
        $id = $request->route('id');
        $marca = Marca::find($id);
        if (!$marca) {
            return redirect('/marca')->with('error', 'Marca no encontrada');
        }
        $marca->nombre = $request->input('nombre');
        $marca->proveedor_id = $request->input('proveedor_id');
        $marca->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'marca_' . $marca->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/marcas', $nombre, 'public');
            $marca->imagen = url('storage/' . $ruta);
            $marca->save();
        }

        return redirect('/marca')->with('success', 'Marca actualizada');
    }

    public function guardar(Request $request)
    {
        $marca = new Marca();
        $marca->nombre = $request->input('nombre');
        $marca->proveedor_id = $request->input('proveedor_id');
        $marca->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'marca_' . $marca->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/marcas', $nombre, 'public');
            $marca->imagen = url('storage/' . $ruta);
            $marca->save();
        }

        return redirect('/marca')->with('success', 'Marca guardada exitosamente.');
    }

    public function eliminar(Request $request)
    {
        $id = $request->route('id');
        $marca = Marca::find($id);
        if (!$marca) {
            return redirect('/marca')->with('error', 'Marca no encontrada');
        }
        $marca->delete();
        return redirect('/marca')->with('success', 'Marca eliminada');
    }

    // Alta rápida desde el formulario de producto (vía AJAX), sin salir de la
    // página ni pedir imagen. Devuelve JSON en vez de redirigir.
    public function guardarRapido(Request $request)
    {
        $nombre = trim((string) $request->input('nombre'));
        $proveedorId = $request->input('proveedor_id');
        $proveedor = $proveedorId ? Proveedor::find($proveedorId) : null;

        if ($nombre === '' || !$proveedor) {
            return response()->json(['error' => 'Escribe un nombre y elige un proveedor.'], 422);
        }

        $marca = new Marca();
        $marca->nombre = $nombre;
        $marca->proveedor_id = $proveedor->id;
        $marca->save();

        return response()->json(['id' => $marca->id, 'nombre' => $marca->nombre]);
    }

    public function mostrar(Request $request)
    {
        $id = $request->route('id');
        $marca = Marca::find($id);
        if (!$marca) {
            return redirect('/marca')->with('error', 'Marca no encontrada');
        }
        return view('marca/borrado', ['marca' => $marca]);
    }
}
