<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RuntimeException;

class ProductoController extends ApiFrontController
{
    public function inicio(Request $request)
    {
        // Reenviamos los filtros del formulario (Blade) tal cual hacia la
        // API real — mismo patrón que usamos en PedidoController::inicio().
        $filtros = array_filter($request->only(['marca', 'proveedor', 'estatus']));
        $ruta = '/api/productos' . ($filtros ? '?' . http_build_query($filtros) : '');

        try {
            $payload = $this->client()->get($ruta, $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/login')->withErrors(['usuario' => $exception->getMessage()]);
        }

        $grupos = $this->normalizeCollection($payload['grupos'] ?? []);

        return view('producto/inicio', compact('grupos'));
    }

    public function formulario()
    {
        try {
            $marcasPayload = $this->client()->get('/api/marcas', $this->token());
            $proveedoresPayload = $this->client()->get('/api/proveedores', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/producto')->withErrors(['producto' => $exception->getMessage()]);
        }

        $marcas = $this->normalizeCollection($marcasPayload['marcas'] ?? []);
        $proveedores = $this->normalizeCollection($proveedoresPayload['proveedores'] ?? []);

        return view('producto/formulario', compact('marcas', 'proveedores'));
    }

    public function guardar(Request $request)
    {
        $data = $request->except(['_token']);
        $data['tallas'] = array_values(array_filter($request->input('tallas', []), fn ($talla) => $talla !== ''));

        foreach (['imagen1', 'imagen2', 'imagen3'] as $campo) {
            if ($request->hasFile($campo)) {
                $data[$campo] = $request->file($campo);
            }
        }

        try {
            $this->client()->post('/api/productos', $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['producto' => $exception->getMessage()])->withInput();
        }

        return redirect('/producto')->with('success', 'Producto guardado correctamente.');
    }

    public function editar(string $id)
    {
        try {
            $payload = $this->client()->get('/api/productos/' . $id, $this->token());
            $marcasPayload = $this->client()->get('/api/marcas', $this->token());
            $proveedoresPayload = $this->client()->get('/api/proveedores', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/producto')->withErrors(['producto' => $exception->getMessage()]);
        }

        $producto = $this->normalizePayload($payload['producto'] ?? null);
        $marcas = $this->normalizeCollection($marcasPayload['marcas'] ?? []);
        $proveedores = $this->normalizeCollection($proveedoresPayload['proveedores'] ?? []);
        $categorias = $this->normalizeCollection($payload['categorias'] ?? []);

        return view('producto/edicion', compact('producto', 'marcas', 'proveedores', 'categorias'));
    }

    public function actualizar(Request $request, string $id)
    {
        $data = $request->except(['_token', '_method']);

        foreach (['imagen1', 'imagen2', 'imagen3'] as $campo) {
            if ($request->hasFile($campo)) {
                $data[$campo] = $request->file($campo);
            }
        }

        try {
            $this->client()->put('/api/productos/' . $id, $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['producto' => $exception->getMessage()])->withInput();
        }

        return redirect('/producto')->with('success', 'Producto actualizado correctamente.');
    }

    public function mostrar(string $id)
    {
        try {
            $payload = $this->client()->get('/api/productos/' . $id, $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/producto')->withErrors(['producto' => $exception->getMessage()]);
        }

        $producto = $this->normalizePayload($payload['producto'] ?? null);

        return view('producto/borrado', compact('producto'));
    }

    public function eliminar(Request $request, string $id)
    {
        try {
            $payload = $this->client()->delete('/api/productos/' . $id, $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/producto')->withErrors(['producto' => $exception->getMessage()]);
        }

        return redirect('/producto')->with('success', $payload['message'] ?? 'Producto eliminado correctamente.');
    }
}
