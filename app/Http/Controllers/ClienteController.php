<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function inicio()
    {
        $clientes = Cliente::all();

        return view('cliente/inicio', compact('clientes'));
    }

    public function formulario()
    {
        return view('cliente/formulario');
    }

    public function editar(Request $request)
    {
        $id = $request->route('id');
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return redirect('/cliente')->with('error', 'Cliente no encontrado');
        }
        return view('cliente/edicion', ['cliente' => $cliente]);
    }


    public function actualizar(Request $request)
    {
        $id = $request->route('id');
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return redirect('/cliente')->with('error', 'Cliente no encontrado');
        }
        $cliente->nombre = $request->input('nombre');
        $cliente->apellido_materno = $request->input('apellido_materno');
        $cliente->apellido_paterno = $request->input('apellido_paterno');
        $cliente->correo = $request->input('correo');
        $cliente->telefono = $request->input('telefono');
        if ($request->filled('contrasena')) {
            $cliente->contrasena = $request->input('contrasena');
        }
        $cliente->estatus = $request->input('estatus');
        $cliente->usuario = $request->input('usuario');
        $cliente->calle = $request->input('calle');
        $cliente->numero = $request->input('numero');
        $cliente->municipio = $request->input('municipio');
        $cliente->codigo_postal = $request->input('codigo_postal');
        $cliente->save();

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'cliente_' . $cliente->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/clientes', $nombre, 'public');
            $cliente->imagen = url('storage/' . $ruta);
            $cliente->save();
        }

        return redirect('/cliente')->with('success', 'Cliente actualizado');
    }

    public function guardar(Request $request)
    {
        $cliente = new Cliente();
        $cliente->nombre = $request->input('nombre');
        $cliente->apellido_materno = $request->input('apellido_materno');
        $cliente->apellido_paterno = $request->input('apellido_paterno');
        $cliente->correo = $request->input('correo');
        $cliente->telefono = $request->input('telefono');
        $cliente->contrasena = $request->input('contrasena');
        $cliente->estatus = $request->input('estatus');
        $cliente->usuario = $request->input('usuario');
        $cliente->calle = $request->input('calle');
        $cliente->numero = $request->input('numero');
        $cliente->municipio = $request->input('municipio');
        $cliente->codigo_postal = $request->input('codigo_postal');
        $cliente->imagen = 'sin-imagen.jpg';

        $cliente->save();
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $nombre = 'cliente_' . $cliente->id . '.' . $file->getClientOriginalExtension();
            $ruta = $file->storeAs('imagenes/clientes', $nombre, 'public');
            $cliente->imagen = url('storage/' . $ruta);
            $cliente->save();
        }

        return redirect('/cliente')->with('success', 'Cliente guardado exitosamente.');
    }


       public function eliminar(Request $request)
    {
        $id = $request->route('id');
        $cliente = Cliente::find($id);
        if(!$cliente){
            return redirect('/cliente')->with('error','Cliente no encontrado');
        }
        $cliente->estatus = 'inactivo';
        $cliente->save();
        return redirect('/cliente')->with('success', 'Cliente eliminado');
    }

        public function mostrar(Request $request)
    {
        $id = $request->route('id');
        $cliente = Cliente::find($id);
        if(!$cliente){
            return redirect('/cliente')->with('error','Cliente no encontrado');
        }
        return view('cliente/borrado',['cliente' => $cliente]);
    }

    public function cambiarEstado(Request $request)
    {
        $id = $request->route('id');
        $cliente = Cliente::find($id);
        if(!$cliente){
            return redirect('/cliente')->with('error','Cliente no encontrado');
        }
        $cliente->estatus = ($cliente->estatus === 'activo') ? 'inactivo' : 'activo';
        $cliente->save();
        return redirect('/cliente')->with('success', 'Estado del cliente actualizado');
    }

}
