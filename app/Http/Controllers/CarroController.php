<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RuntimeException;

class CarroController extends ApiFrontController
{
    public function inicio()
    {
        try {
            $payload = $this->client()->get('/api/carros', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/login')->withErrors(['usuario' => $exception->getMessage()]);
        }

        $carros = $this->normalizeCollection($payload['carros'] ?? []);

        return view('carro/inicio', compact('carros'));
    }

    public function formulario()
    {
        return view('carro/formulario');
    }

    public function guardar(Request $request)
    {
        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $data['imagen'] = $archivo;
        }

        try {
            $this->client()->post('/api/carros', $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['carro' => $exception->getMessage()])->withInput();
        }

        return redirect('/carro')->with('success', 'Carro guardado exitosamente.');
    }

    public function editar(string $id)
    {
        try {
            $payload = $this->client()->get("/api/carros/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/carro')->withErrors(['carro' => $exception->getMessage()]);
        }

        $carro = $this->normalizePayload($payload['carro'] ?? null);

        return view('carro/edicion', compact('carro'));
    }

    public function actualizar(Request $request, string $id)
    {
        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $data['imagen'] = $archivo;
        }

        try {
            $this->client()->put("/api/carros/{$id}", $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['carro' => $exception->getMessage()])->withInput();
        }

        return redirect('/carro')->with('success', 'Carro actualizado correctamente.');
    }

    public function mostrar(string $id)
    {
        try {
            $payload = $this->client()->get("/api/carros/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/carro')->withErrors(['carro' => $exception->getMessage()]);
        }

        $carro = $this->normalizePayload($payload['carro'] ?? null);

        return view('carro/borrado', compact('carro'));
    }

    public function eliminar(string $id)
    {
        try {
            $payload = $this->client()->delete("/api/carros/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/carro')->withErrors(['carro' => $exception->getMessage()]);
        }

        return redirect('/carro')->with('success', $payload['message'] ?? 'Carro eliminado correctamente.');
    }
}
