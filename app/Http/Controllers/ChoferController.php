<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RuntimeException;

class ChoferController extends ApiFrontController
{
    public function inicio()
    {
        try {
            $payload = $this->client()->get('/api/choferes', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/login')->withErrors(['usuario' => $exception->getMessage()]);
        }

        $choferes = $this->normalizeCollection($payload['choferes'] ?? []);

        return view('chofer/inicio', compact('choferes'));
    }

    public function formulario()
    {
        return view('chofer/formulario');
    }

    public function guardar(Request $request)
    {
        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $data['imagen'] = $archivo;
        }

        try {
            $this->client()->post('/api/choferes', $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['chofer' => $exception->getMessage()])->withInput();
        }

        return redirect('/chofer')->with('success', 'Chofer guardado exitosamente.');
    }

    public function editar(string $id)
    {
        try {
            $payload = $this->client()->get("/api/choferes/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/chofer')->withErrors(['chofer' => $exception->getMessage()]);
        }

        $chofer = $this->normalizePayload($payload['chofer'] ?? null);

        return view('chofer/edicion', compact('chofer'));
    }

    public function actualizar(Request $request, string $id)
    {
        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $data['imagen'] = $archivo;
        }

        try {
            $this->client()->put("/api/choferes/{$id}", $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['chofer' => $exception->getMessage()])->withInput();
        }

        return redirect('/chofer')->with('success', 'Chofer actualizado correctamente.');
    }

    public function mostrar(string $id)
    {
        try {
            $payload = $this->client()->get("/api/choferes/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/chofer')->withErrors(['chofer' => $exception->getMessage()]);
        }

        $chofer = $this->normalizePayload($payload['chofer'] ?? null);

        return view('chofer/borrado', compact('chofer'));
    }

    public function eliminar(string $id)
    {
        try {
            $payload = $this->client()->delete("/api/choferes/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/chofer')->withErrors(['chofer' => $exception->getMessage()]);
        }

        return redirect('/chofer')->with('success', $payload['message'] ?? 'Chofer eliminado correctamente.');
    }
}
