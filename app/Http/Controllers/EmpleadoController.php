<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RuntimeException;

class EmpleadoController extends ApiFrontController
{
    public function inicio()
    {
        try {
            $payload = $this->client()->get('/api/empleados', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/login')->withErrors(['usuario' => $exception->getMessage()]);
        }

        $empleados = $this->normalizeCollection($payload['empleados'] ?? []);

        return view('empleado/inicio', compact('empleados'));
    }

    public function formulario()
    {
        return view('empleado/formulario');
    }

    public function guardar(Request $request)
    {
        try {
            $data = $request->except('imagen');

            if ($request->hasFile('imagen')) {
                $archivo = $request->file('imagen');
                $data['imagen'] = $archivo; // El cliente ya maneja attach
            }

            $this->client()->post('/api/empleados', $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['empleado' => $exception->getMessage()])->withInput();
        }

        return redirect('/empleado')->with('success', 'Empleado guardado exitosamente.');
    }

    public function editar(string $id)
    {
        try {
            $payload = $this->client()->get("/api/empleados/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/empleado')->withErrors(['empleado' => $exception->getMessage()]);
        }

        $empleado = $this->normalizePayload($payload['empleado'] ?? null);

        return view('empleado/edicion', compact('empleado'));
    }

    public function actualizar(Request $request, string $id)
    {
        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $archivo = $request->file('imagen');
            $data['imagen'] = $archivo;
        }

        try {
            $this->client()->put("/api/empleados/{$id}", $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['empleado' => $exception->getMessage()])->withInput();
        }

        return redirect('/empleado')->with('success', 'Empleado actualizado correctamente.');
    }

    public function mostrar(string $id)
    {
        try {
            $payload = $this->client()->get("/api/empleados/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/empleado')->withErrors(['empleado' => $exception->getMessage()]);
        }

        $empleado = $this->normalizePayload($payload['empleado'] ?? null);

        return view('empleado/borrado', compact('empleado'));
    }

    public function eliminar(string $id)
    {
        try {
            $payload = $this->client()->delete("/api/empleados/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/empleado')->withErrors(['empleado' => $exception->getMessage()]);
        }

        return redirect('/empleado')->with('success', $payload['message'] ?? 'Empleado eliminado correctamente.');
    }
}
