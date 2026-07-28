<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Empleado;

class PedidoController extends Controller
{
    //
    public function inicio()
    {
        $pedidos = Pedido::with('empleado')->get();

        return view('pedido/inicio', compact('pedidos'));
    }

    public function formulario()
    {
        $empleados = Empleado::all();

        return view('pedido/formulario', compact('empleados'));
    }

    public function editar(Request $request)
    {
        $id = $request->route('id');
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return redirect('/pedido')->with('error', 'Pedido no encontrado');
        }
        $empleados = Empleado::all();

        return view('pedido/edicion', compact('pedido', 'empleados'));
    }

    public function actualizar(Request $request)
    {
        $id = $request->route('id');
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return redirect('/pedido')->with('error', 'Pedido no encontrado');
        }
        $pedido->empleado_id = $request->input('empleado_id');
        $pedido->estatus = $request->input('estatus');
        $pedido->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'pedido_' . $pedido->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/pedidos', $nombre, 'public');
            $pedido->imagen = url('storage/' . $ruta);
            $pedido->save();
        }

        return redirect('/pedido')->with('success', 'Pedido actualizado');
    }

    public function guardar(Request $request)
    {
        $pedido = new Pedido();
        $pedido->empleado_id = $request->input('empleado_id');
        $pedido->estatus = $request->input('estatus');
        $pedido->imagen = 'sin-imagen.jpg';
        $pedido->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'pedido_' . $pedido->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/pedidos', $nombre, 'public');
            $pedido->imagen = url('storage/' . $ruta);
            $pedido->save();
        }

        return redirect('/pedido')->with('success', 'Pedido guardado exitosamente.');
    }

    public function eliminar(Request $request)
    {
        $id = $request->route('id');
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return redirect('/pedido')->with('error', 'Pedido no encontrado');
        }
        $pedido->estatus = 'Cancelado';
        $pedido->save();
        return redirect('/pedido')->with('success', 'Pedido cancelado');
    }

    public function mostrar(Request $request)
    {
        $id = $request->route('id');
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return redirect('/pedido')->with('error', 'Pedido no encontrado');
        }
        return view('pedido/borrado', ['pedido' => $pedido]);
    }
}
