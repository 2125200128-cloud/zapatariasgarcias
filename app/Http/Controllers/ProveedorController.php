<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RuntimeException;

class ProveedorController extends ApiFrontController
{
    public function inicio()
    {
        try {
            $payload = $this->client()->get('/api/proveedores', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/login')->withErrors(['usuario' => $exception->getMessage()]);
        }

        $proveedores = $this->normalizeCollection($payload['proveedores'] ?? []);

        return view('proveedor/inicio', compact('proveedores'));
    }

    public function formulario()
    {
        // Proveedor no necesita catálogos, cargamos la vista directo
        return view('proveedor/formulario');
    }

    public function guardar(Request $request)
    {
        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $data['imagen'] = $archivo;
        }

        try {
            $this->client()->post('/api/proveedores', $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['proveedor' => $exception->getMessage()])->withInput();
        }

        return redirect('/proveedor')->with('success', 'Proveedor guardado exitosamente.');
    }

    public function editar(string $id)
    {
        try {
            $payload = $this->client()->get("/api/proveedores/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/proveedor')->withErrors(['proveedor' => $exception->getMessage()]);
        }

        $proveedor = $this->normalizePayload($payload['proveedor'] ?? null);

        return view('proveedor/edicion', compact('proveedor'));
    }

    public function actualizar(Request $request, string $id)
    {
        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $data['imagen'] = $archivo;
        }

        try {
            $this->client()->put("/api/proveedores/{$id}", $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['proveedor' => $exception->getMessage()])->withInput();
        }

        return redirect('/proveedor')->with('success', 'Proveedor actualizado correctamente.');
    }

    public function mostrar(string $id)
    {
        try {
            $payload = $this->client()->get("/api/proveedores/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/proveedor')->withErrors(['proveedor' => $exception->getMessage()]);
        }

        $proveedor = $this->normalizePayload($payload['proveedor'] ?? null);

        return view('proveedor/borrado', compact('proveedor'));
    }

    public function eliminar(string $id)
    {
        try {
            $payload = $this->client()->delete("/api/proveedores/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/proveedor')->withErrors(['proveedor' => $exception->getMessage()]);
        }

        return redirect('/proveedor')->with('success', $payload['message'] ?? 'Proveedor eliminado correctamente.');
    }
}
