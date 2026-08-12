<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RuntimeException;

class SucursalController extends ApiFrontController
{
    public function inicio()
    {
        try {
            $payload = $this->client()->get('/api/sucursales', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/login')->withErrors(['usuario' => $exception->getMessage()]);
        }

        $sucursales = $this->normalizeCollection($payload['sucursales'] ?? []);

        return view('sucursal/inicio', compact('sucursales'));
    }

    public function formulario()
    {
       
        return view('sucursal/formulario');
    }

    public function guardar(Request $request)
    {
        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $data['imagen'] = $archivo;
        }

        try {
            $this->client()->post('/api/sucursales', $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['sucursal' => $exception->getMessage()])->withInput();
        }

        return redirect('/sucursal')->with('success', 'Sucursal guardada exitosamente.');
    }

    public function editar(string $id)
    {
        try {
            $payload = $this->client()->get("/api/sucursales/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/sucursal')->withErrors(['sucursal' => $exception->getMessage()]);
        }

        $sucursal = $this->normalizePayload($payload['sucursal'] ?? null);
        $empleados = $this->normalizeCollection($payload['empleados'] ?? []);

        return view('sucursal/edicion', compact('sucursal', 'empleados'));
    }

    public function actualizar(Request $request, string $id)
    {
        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $data['imagen'] = $archivo;
        }

        try {
            $this->client()->put("/api/sucursales/{$id}", $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['sucursal' => $exception->getMessage()])->withInput();
        }

        return redirect('/sucursal')->with('success', 'Sucursal actualizada correctamente.');
    }

    public function mostrar(string $id)
    {
        try {
            $payload = $this->client()->get("/api/sucursales/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/sucursal')->withErrors(['sucursal' => $exception->getMessage()]);
        }

        $sucursal = $this->normalizePayload($payload['sucursal'] ?? null);

        return view('sucursal/borrado', compact('sucursal'));
    }

    public function eliminar(string $id)
    {
        try {
            $payload = $this->client()->delete("/api/sucursales/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/sucursal')->withErrors(['sucursal' => $exception->getMessage()]);
        }

        return redirect('/sucursal')->with('success', $payload['message'] ?? 'Sucursal eliminada correctamente.');
    }
}
