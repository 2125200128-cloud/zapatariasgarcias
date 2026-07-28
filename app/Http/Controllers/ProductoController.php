<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Proveedor;

class ProductoController extends Controller
{
    //
    public function inicio()
    {
        $productos = Producto::with(['marca', 'proveedor'])->get();

        return view('producto/inicio', compact('productos'));
    }

    public function formulario()
    {
        $marcas = Marca::all();
        $proveedores = Proveedor::all();

        return view('producto/formulario', compact('marcas', 'proveedores'));
    }

    public function editar(Request $request)
    {
        $id = $request->route('id');
        $producto = Producto::find($id);
        if (!$producto) {
            return redirect('/producto')->with('error', 'Producto no encontrado');
        }
        $marcas = Marca::all();
        $proveedores = Proveedor::all();

        return view('producto/edicion', compact('producto', 'marcas', 'proveedores'));
    }

    public function actualizar(Request $request)
    {
        $id = $request->route('id');
        $producto = Producto::find($id);
        if (!$producto) {
            return redirect('/producto')->with('error', 'Producto no encontrado');
        }
        $producto->nombre = $request->input('nombre');
        $producto->descripcion = $request->input('descripcion');
        $producto->marca_id = $request->input('marca_id');
        $producto->proveedor_id = $request->input('proveedor_id');
        $producto->precio = $request->input('precio');
        $producto->modelo = $request->input('modelo');
        $producto->color = $request->input('color');
        $producto->sexo = $request->input('sexo');
        $producto->categoria = $request->input('categoria');
        $producto->talla = $request->input('talla');
        $producto->estatus = $request->input('estatus');
        $producto->save();

        foreach (['imagen1', 'imagen2', 'imagen3'] as $campo) {
            if ($request->hasFile($campo)) {
                $file = $request->file($campo);
                $nombre = 'producto_' . $producto->id . '_' . $campo . '.' . $file->getClientOriginalExtension();
                $ruta = $file->storeAs('imagenes/productos', $nombre, 'public');
                $producto->{$campo} = url('storage/' . $ruta);
                $producto->save();
            }
        }

        return redirect('/producto')->with('success', 'Producto actualizado');
    }

    public function guardar(Request $request)
    {
        $producto = new Producto();
        $producto->nombre = $request->input('nombre');
        $producto->descripcion = $request->input('descripcion');
        $producto->marca_id = $request->input('marca_id');
        $producto->proveedor_id = $request->input('proveedor_id');
        $producto->precio = $request->input('precio');
        $producto->modelo = $request->input('modelo');
        $producto->color = $request->input('color');
        $producto->sexo = $request->input('sexo');
        $producto->categoria = $request->input('categoria');
        $producto->talla = $request->input('talla');
        $producto->estatus = $request->input('estatus');
        $producto->imagen1 = 'sin-imagen.jpg';
        $producto->save();

        foreach (['imagen1', 'imagen2', 'imagen3'] as $campo) {
            if ($request->hasFile($campo)) {
                $file = $request->file($campo);
                $nombre = 'producto_' . $producto->id . '_' . $campo . '.' . $file->getClientOriginalExtension();
                $ruta = $file->storeAs('imagenes/productos', $nombre, 'public');
                $producto->{$campo} = url('storage/' . $ruta);
                $producto->save();
            }
        }

        return redirect('/producto')->with('success', 'Producto guardado exitosamente.');
    }

    public function eliminar(Request $request)
    {
        $id = $request->route('id');
        $producto = Producto::find($id);
        if (!$producto) {
            return redirect('/producto')->with('error', 'Producto no encontrado');
        }
        $producto->delete();
        return redirect('/producto')->with('success', 'Producto eliminado');
    }

    public function mostrar(Request $request)
    {
        $id = $request->route('id');
        $producto = Producto::find($id);
        if (!$producto) {
            return redirect('/producto')->with('error', 'Producto no encontrado');
        }
        return view('producto/borrado', ['producto' => $producto]);
    }
}
