<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RuntimeException;

class MarcaController extends ApiFrontController
{
    public function inicio()
    {
        try {
            $payload = $this->client()->get('/api/marcas', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/login')->withErrors(['usuario' => $exception->getMessage()]);
        }

        $marcas = $this->normalizeCollection($payload['marcas'] ?? []);

        return view('marca/inicio', compact('marcas'));
    }

    public function formulario()
    {
        try {
            $payload = $this->client()->get('/api/marcas/formulario', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/marca')->withErrors(['marca' => $exception->getMessage()]);
        }

        $proveedores = $this->normalizeCollection($payload['proveedores'] ?? []);

        return view('marca/formulario', compact('proveedores'));
    }

    public function guardar(Request $request)
    {
        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $data['imagen'] = $archivo;
        }

        try {
            $this->client()->post('/api/marcas', $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['marca' => $exception->getMessage()])->withInput();
        }

        return redirect('/marca')->with('success', 'Marca guardada exitosamente.');
    }

    public function editar(string $id)
    {
        try {
            $payload = $this->client()->get("/api/marcas/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/marca')->withErrors(['marca' => $exception->getMessage()]);
        }

        $marca = $this->normalizePayload($payload['marca'] ?? null);
        $proveedores = $this->normalizeCollection($payload['proveedores'] ?? []);

        return view('marca/edicion', compact('marca', 'proveedores'));
    }

    public function actualizar(Request $request, string $id)
    {
        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $data['imagen'] = $archivo;
        }

        try {
            $this->client()->put("/api/marcas/{$id}", $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['marca' => $exception->getMessage()])->withInput();
        }

        return redirect('/marca')->with('success', 'Marca actualizada correctamente.');
    }

    public function mostrar(string $id)
    {
        try {
            $payload = $this->client()->get("/api/marcas/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/marca')->withErrors(['marca' => $exception->getMessage()]);
        }

        $marca = $this->normalizePayload($payload['marca'] ?? null);

        return view('marca/borrado', compact('marca'));
    }

    public function eliminar(string $id)
    {
        try {
            $payload = $this->client()->delete("/api/marcas/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/marca')->withErrors(['marca' => $exception->getMessage()]);
        }

        return redirect('/marca')->with('success', $payload['message'] ?? 'Marca eliminada correctamente.');
    }

    public function guardarRapido(Request $request)
    {
        try {
            $payload = $this->client()->post('/api/marcas/rapido', $request->all(), $this->token());
            return response()->json($payload, 201);
        } catch (RuntimeException $exception) {
            return response()->json(['error' => 'No se pudo guardar la marca rápida.'], 422);
        }
    }
}
