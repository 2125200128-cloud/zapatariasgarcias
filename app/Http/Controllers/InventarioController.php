<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empleado;
use App\Models\Inventario;
use App\Models\Sucursal;
use App\Models\Producto;

class InventarioController extends Controller
{
    // El inventario es cosa de la matriz (Administrador o el Encargado de la
    // matriz) — una sucursal no registra ni edita inventario, el suyo se
    // abona solo al confirmar la llegada de un trayecto.
    private function puedeAsignar(): bool
    {
        $empleado = Empleado::auth();
        return $empleado !== null && ($empleado->esAdministrador() || $empleado->esMatriz());
    }

    public function inicio()
    {
        $query = Inventario::with(['sucursal', 'producto.marca']);

        // La matriz ve todo; una sucursal solo ve su propio stock (de solo
        // lectura — registrar/editar sigue siendo exclusivo de la matriz).
        if (!$this->puedeAsignar()) {
            $miSucursal = Empleado::auth()?->miSucursal();
            $query->where('sucursal_id', $miSucursal?->id ?? 0);
        }

        $inventarios = $query->get();

        return view('inventario/inicio', compact('inventarios'));
    }

    public function formulario()
    {
        if (!$this->puedeAsignar()) {
            abort(403, 'El inventario solo lo gestiona la matriz.');
        }

        $sucursales = Sucursal::all();
        $productos = Producto::with('marca')->get();

        return view('inventario/formulario', compact('sucursales', 'productos'));
    }

    public function editar(Request $request)
    {
        if (!$this->puedeAsignar()) {
            abort(403, 'El inventario solo lo gestiona la matriz.');
        }

        $id = $request->route('id');
        $inventario = Inventario::find($id);
        if (!$inventario) {
            return redirect('/inventario')->with('error', 'Registro no encontrado');
        }
        $sucursales = Sucursal::all();
        $productos = Producto::with('marca')->get();

        return view('inventario/edicion', compact('inventario', 'sucursales', 'productos'));
    }

    public function actualizar(Request $request)
    {
        if (!$this->puedeAsignar()) {
            abort(403, 'El inventario solo lo gestiona la matriz.');
        }

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
        if (!$this->puedeAsignar()) {
            abort(403, 'El inventario solo lo gestiona la matriz.');
        }

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
        if (!$this->puedeAsignar()) {
            abort(403, 'El inventario solo lo gestiona la matriz.');
        }

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
        if (!$this->puedeAsignar()) {
            abort(403, 'El inventario solo lo gestiona la matriz.');
        }

        $id = $request->route('id');
        $inventario = Inventario::find($id);
        if (!$inventario) {
            return redirect('/inventario')->with('error', 'Registro no encontrado');
        }
        return view('inventario/borrado', ['inventario' => $inventario]);
    }
}
