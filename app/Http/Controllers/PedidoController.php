<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use RuntimeException;


class PedidoController extends ApiFrontController
{
    // La sucursal de un pedido no viaja como campo plano desde la API:
    // llega anidada en empleado.sucursales[0] (en listados) o suelta como
    // 'sucursalActual' (en editar/mostrar). La normalizamos aquí a
    // $pedido->sucursal para que las vistas puedan seguir usando
    // data_get($pedido, 'sucursal.nombre') sin cambios.
    // OJO: $pedido es un stdClass (normalizePayload lo convierte así),
    // por eso se asigna como propiedad de objeto, no como índice de array.
    private function inyectarSucursal($pedido, $sucursalActual = null)
    {
        if (!$pedido) {
            return $pedido;
        }

        $sucursal = $sucursalActual ?? data_get($pedido, 'empleado.sucursales.0');

        if ($sucursal) {
            $pedido->sucursal = $sucursal;
        }

        return $pedido;
    }

    private function inyectarSucursalEnColeccion(Collection $pedidos): Collection
    {
        return $pedidos->map(fn ($pedido) => $this->inyectarSucursal($pedido));
    }

    public function inicio()
    {
        try {
            $payload = $this->client()->get('/api/pedidos', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/login')->withErrors(['usuario' => $exception->getMessage()]);
        }

        $pedidos = $this->inyectarSucursalEnColeccion(
            $this->normalizeCollection($payload['pedidos'] ?? [])
        );

        return view('pedido/inicio', compact('pedidos'));
    }

    public function formulario()
    {
        try {
            $payload = $this->client()->get('/api/pedidos/formulario', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/pedido')->withErrors(['pedido' => $exception->getMessage()]);
        }

        $sucursales = $this->normalizeCollection($payload['sucursales'] ?? []);
        $productos = $this->normalizeCollection($payload['productos'] ?? []);
        $sucursalFija = $this->normalizePayload($payload['sucursalFija'] ?? null);
        // stockMatriz llega como objeto JSON {producto_id: stock}; se deja
        // como array asociativo tal cual (sin normalizePayload) porque
        // formulario.blade.php lo indexa con $stockMatriz[$id] ?? 0.
        $stockMatriz = $payload['stockMatriz'] ?? [];

        return view('pedido/formulario', compact('sucursales', 'productos', 'sucursalFija', 'stockMatriz'));
    }

    public function guardar(Request $request)
    {
        $data = [
            'sucursal_id' => $request->input('sucursal_id'),
            'producto_id' => array_values(array_filter($request->input('producto_id', []), fn ($id) => $id !== '')),
            'cantidad' => array_values(array_filter($request->input('cantidad', []), fn ($cantidad) => $cantidad !== '')),
        ];

        try {
            $this->client()->post('/api/pedidos', $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['pedido' => $exception->getMessage()])->withInput();
        }

        return redirect('/pedido')->with('success', 'Pedido creado correctamente.');
    }

    public function editar(string $id)
    {
        try {
            $payload = $this->client()->get('/api/pedidos/' . $id, $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/pedido')->withErrors(['pedido' => $exception->getMessage()]);
        }

        $sucursalActual = $this->normalizePayload($payload['sucursalActual'] ?? null);
        $pedido = $this->inyectarSucursal(
            $this->normalizePayload($payload['pedido'] ?? null),
            $sucursalActual
        );
        $sucursales = $this->normalizeCollection($payload['sucursales'] ?? []);
        $productos = $this->normalizeCollection($payload['productos'] ?? []);

        return view('pedido/edicion', compact('pedido', 'sucursales', 'productos', 'sucursalActual'));
    }

    public function actualizar(Request $request, string $id)
    {
        $data = [
            'sucursal_id' => $request->input('sucursal_id'),
            'producto_id' => array_values(array_filter($request->input('producto_id', []), fn ($productoId) => $productoId !== '')),
            'cantidad' => array_values(array_filter($request->input('cantidad', []), fn ($cantidad) => $cantidad !== '')),
            'estatus' => $request->input('estatus', 'Pendiente'),
        ];

        try {
            $this->client()->put('/api/pedidos/' . $id, $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['pedido' => $exception->getMessage()])->withInput();
        }

        return redirect('/pedido')->with('success', 'Pedido actualizado correctamente.');
    }

    public function mostrar(string $id)
    {
        try {
            $payload = $this->client()->get('/api/pedidos/' . $id, $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/pedido')->withErrors(['pedido' => $exception->getMessage()]);
        }

        $sucursalActual = $this->normalizePayload($payload['sucursalActual'] ?? null);
        $pedido = $this->inyectarSucursal(
            $this->normalizePayload($payload['pedido'] ?? null),
            $sucursalActual
        );

        return view('pedido/borrado', compact('pedido'));
    }

    public function eliminar(string $id)
    {
        try {
            $payload = $this->client()->delete('/api/pedidos/' . $id, $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/pedido')->withErrors(['pedido' => $exception->getMessage()]);
        }

        return redirect('/pedido')->with('success', $payload['message'] ?? 'Pedido cancelado correctamente.');
    }

    public function pendientes()
    {
        try {
            $payload = $this->client()->get('/api/pedidos/pending', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/pedido')->withErrors(['pedido' => $exception->getMessage()]);
        }

        $pedidos = $this->inyectarSucursalEnColeccion(
            $this->normalizeCollection($payload['pedidos'] ?? [])
        );

        return view('pedido/pendientes', compact('pedidos'));
    }

    public function aceptar(string $id)
    {
        try {
            $payload = $this->client()->get('/api/pedidos/' . $id . '/assign', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/pedido/pendientes')->withErrors(['pedido' => $exception->getMessage()]);
        }

        $pedido = $this->inyectarSucursal($this->normalizePayload($payload['pedido'] ?? null));
        $choferes = $this->normalizeCollection($payload['choferes'] ?? []);
        $carros = $this->normalizeCollection($payload['carros'] ?? []);

        return view('pedido/aceptar', compact('pedido', 'choferes', 'carros'));
    }

    public function procesarAceptar(Request $request, string $id)
    {
        $data = [
            'chofer_id' => $request->input('chofer_id'),
            'carro_id' => $request->input('carro_id'),
        ];

        try {
            $payload = $this->client()->post('/api/pedidos/' . $id . '/accept', $data, $this->token());
        } catch (RuntimeException $exception) {
            // Vuelve al mismo formulario (no a la lista), porque
            // aceptar.blade.php solo lee session('error').
            return redirect('/pedido/' . $id . '/aceptar')->with('error', $exception->getMessage());
        }

        return redirect('/pedido/pendientes')->with('success', $payload['message'] ?? 'Pedido aceptado y entrega asignada.');
    }

    public function pdf(string $id)
{
    try {
        // Traer datos del pedido desde la API
        $payload = $this->client()->get('/api/pedidos/' . $id, $this->token());
    } catch (\RuntimeException $exception) {
        return redirect('/pedido')->withErrors(['pedido' => $exception->getMessage()]);
    }

    $sucursalActual = $this->normalizePayload($payload['sucursalActual'] ?? null);
    $pedido = $this->normalizePayload($payload['pedido'] ?? null);

    // Renderizar PDF con Blade en el front
    $pdf = Pdf::loadView('pedido/pdf', compact('pedido', 'sucursalActual'));

    return $pdf->stream('nota-pedido-' . $id . '.pdf');
}
}