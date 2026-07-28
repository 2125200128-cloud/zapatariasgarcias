<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Sucursal;
use App\Models\Producto;

class InventarioController extends Controller
{
    //
    public function inicio()
    {
        $inventarios = Inventario::with(['sucursal', 'producto'])->get();

        return view('inventario/inicio', compact('inventarios'));
    }

    public function formulario()
    {
        $sucursales = Sucursal::all();
        $productos = Producto::all();

        return view('inventario/formulario', compact('sucursales', 'productos'));
    }

    public function editar(Request $request)
    {
        $id = $request->route('id');
        $inventario = Inventario::find($id);
        if (!$inventario) {
            return redirect('/inventario')->with('error', 'Registro no encontrado');
        }
        $sucursales = Sucursal::all();
        $productos = Producto::all();

        return view('inventario/edicion', compact('inventario', 'sucursales', 'productos'));
    }

    public function actualizar(Request $request)
    {
        $id = $request->route('id');
        $inventario = Inventario::find($id);
        if (!$inventario) {
            return redirect('/inventario')->with('error', 'Registro no encontrado');
        }
        $inventario->sucursal_id = $request->input('sucursal_id');
        $inventario->producto_id = $request->input('producto_id');
        $inventario->stock = $request->input('stock');
        $inventario->estatus = $request->input('estatus');
        $inventario->save();

        return redirect('/inventario')->with('success', 'Registro actualizado');
    }

    public function guardar(Request $request)
    {
        $inventario = new Inventario();
        $inventario->sucursal_id = $request->input('sucursal_id');
        $inventario->producto_id = $request->input('producto_id');
        $inventario->stock = $request->input('stock');
        $inventario->estatus = $request->input('estatus');
        $inventario->save();

        return redirect('/inventario')->with('success', 'Registro guardado exitosamente.');
    }

    public function eliminar(Request $request)
    {
        $id = $request->route('id');
        $inventario = Inventario::find($id);
        if (!$inventario) {
            return redirect('/inventario')->with('error', 'Registro no encontrado');
        }
        $inventario->delete();
        return redirect('/inventario')->with('success', 'Registro eliminado');
    }

    public function mostrar(Request $request)
    {
        $id = $request->route('id');
        $inventario = Inventario::find($id);
        if (!$inventario) {
            return redirect('/inventario')->with('error', 'Registro no encontrado');
        }
        return view('inventario/borrado', ['inventario' => $inventario]);
    }
}
