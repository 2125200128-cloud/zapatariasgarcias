<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RuntimeException;

class InventarioController extends ApiFrontController
{
    public function inicio()
    {
        try {
            $payload = $this->client()->get('/api/inventarios', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/login')->withErrors(['usuario' => $exception->getMessage()]);
        }

        $inventarios = $this->normalizeCollection($payload['inventarios'] ?? []);
        $puedeGestionar = $this->puedeGestionar();

        return view('inventario/inicio', compact('inventarios', 'puedeGestionar'));
    }

    public function formulario()
    {
        if (!$this->puedeGestionar()) {
            abort(403, 'El inventario solo lo gestiona la matriz.');
        }

        try {
            $payload = $this->client()->get('/api/inventarios/datos-formulario', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/inventario')->withErrors(['inventario' => $exception->getMessage()]);
        }

        $sucursales = $this->normalizeCollection($payload['sucursales'] ?? []);
        $productos = $this->normalizeCollection($payload['productos'] ?? []);

        return view('inventario/formulario', compact('sucursales', 'productos'));
    }

    public function guardar(Request $request)
    {
        if (!$this->puedeGestionar()) {
            abort(403, 'El inventario solo lo gestiona la matriz.');
        }

        try {
            $this->client()->post('/api/inventarios', $request->all(), $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['inventario' => $exception->getMessage()])->withInput();
        }

        return redirect('/inventario')->with('success', 'Registro guardado exitosamente.');
    }

    public function editar(string $id)
    {
        if (!$this->puedeGestionar()) {
            abort(403, 'El inventario solo lo gestiona la matriz.');
        }

        try {
            $resInventario = $this->client()->get("/api/inventarios/{$id}", $this->token());
            $inventario = $this->normalizePayload($resInventario['inventario'] ?? null);

            $resDatos = $this->client()->get('/api/inventarios/datos-formulario', $this->token());
            $sucursales = $this->normalizeCollection($resDatos['sucursales'] ?? []);
            $productos = $this->normalizeCollection($resDatos['productos'] ?? []);
        } catch (RuntimeException $exception) {
            return redirect('/inventario')->withErrors(['inventario' => $exception->getMessage()]);
        }

        return view('inventario/edicion', compact('inventario', 'sucursales', 'productos'));
    }

    public function actualizar(Request $request, string $id)
    {
        if (!$this->puedeGestionar()) {
            abort(403, 'El inventario solo lo gestiona la matriz.');
        }

        try {
            $this->client()->put("/api/inventarios/{$id}", $request->all(), $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['inventario' => $exception->getMessage()])->withInput();
        }

        return redirect('/inventario')->with('success', 'Registro actualizado correctamente.');
    }

    public function mostrar(string $id)
    {
        if (!$this->puedeGestionar()) {
            abort(403, 'El inventario solo lo gestiona la matriz.');
        }

        try {
            $payload = $this->client()->get("/api/inventarios/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/inventario')->withErrors(['inventario' => $exception->getMessage()]);
        }

        $inventario = $this->normalizePayload($payload['inventario'] ?? null);

        return view('inventario/borrado', compact('inventario'));
    }

    public function eliminar(string $id)
    {
        if (!$this->puedeGestionar()) {
            abort(403, 'El inventario solo lo gestiona la matriz.');
        }

        try {
            $payload = $this->client()->delete("/api/inventarios/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/inventario')->withErrors(['inventario' => $exception->getMessage()]);
        }

        return redirect('/inventario')->with('success', $payload['message'] ?? 'Registro eliminado correctamente.');
    }
}