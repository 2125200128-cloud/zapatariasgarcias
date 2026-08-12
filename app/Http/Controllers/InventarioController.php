<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use RuntimeException;

class InventarioController extends ApiFrontController
{
    // Filtro de la barra de búsqueda de /inventario — mismo patrón que
    // PedidoController::filtrarPedidos(), aplicado sobre la colección ya
    // traída de la API.
    private function filtrarInventarios(Collection $inventarios, Request $request): Collection
    {
        $busqueda = mb_strtolower(trim((string) $request->query('busqueda', '')));
        $estatus = (string) $request->query('estatus', '');

        return $inventarios->filter(function ($inventario) use ($busqueda, $estatus) {
            if ($busqueda !== '') {
                $nombre = mb_strtolower((string) data_get($inventario, 'producto.nombre', ''));
                $marca = mb_strtolower((string) data_get($inventario, 'producto.marca.nombre', ''));
                $talla = mb_strtolower((string) data_get($inventario, 'producto.talla', ''));
                if (!str_contains($nombre, $busqueda) && !str_contains($marca, $busqueda) && !str_contains($talla, $busqueda)) {
                    return false;
                }
            }

            if ($estatus !== '' && data_get($inventario, 'estatus') !== $estatus) {
                return false;
            }

            return true;
        })->values();
    }

    public function inicio(Request $request)
    {
        try {
            $payload = $this->client()->get('/api/inventarios', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/login')->withErrors(['usuario' => $exception->getMessage()]);
        }

        $inventarios = $this->normalizeCollection($payload['inventarios'] ?? []);
        $inventarios = $this->filtrarInventarios($inventarios, $request);
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
        // El inventario que se captura a mano es siempre de la matriz — la
        // API ya lo fuerza del lado del servidor, aquí solo lo mostramos
        // fijo en vez de dejar elegir sucursal. Mismo criterio que
        // Sucursal::matriz() en la API: primero por 'es_matriz', si esa
        // columna no está poblada cae al nombre configurado.
        $matriz = $sucursales->first(fn ($s) => data_get($s, 'es_matriz'))
            ?? $sucursales->first(fn ($s) => data_get($s, 'nombre') === config('ubicaciones.matriz.nombre'));
        $nombresProductos = $productos->pluck('nombre')->filter()->unique()->sort()->values();

        return view('inventario/formulario', compact('productos', 'matriz', 'nombresProductos'));
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

    public function formularioReabastecer()
    {
        if (!$this->puedeGestionar()) {
            abort(403, 'El inventario solo lo gestiona la matriz.');
        }

        try {
            $payload = $this->client()->get('/api/inventarios/datos-formulario', $this->token());
            $inventarioActual = $this->client()->get('/api/inventarios', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/inventario')->withErrors(['inventario' => $exception->getMessage()]);
        }

        $productos = $this->normalizeCollection($payload['productos'] ?? []);
        $nombresProductos = $productos->pluck('nombre')->filter()->unique()->sort()->values();

        // /api/inventarios ya viene filtrado a solo la matriz — de ahí
        // sacamos el stock actual de cada talla para mostrarlo junto al
        // chip (ej. "26 (tienes 2)").
        $stockMatriz = $this->normalizeCollection($inventarioActual['inventarios'] ?? [])
            ->mapWithKeys(fn ($item) => [data_get($item, 'producto_id') => data_get($item, 'stock', 0)]);

        return view('inventario/reabastecer', compact('productos', 'nombresProductos', 'stockMatriz'));
    }

    public function reabastecer(Request $request)
    {
        if (!$this->puedeGestionar()) {
            abort(403, 'El inventario solo lo gestiona la matriz.');
        }

        try {
            $this->client()->post('/api/inventarios/reabastecer', $request->all(), $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['inventario' => $exception->getMessage()])->withInput();
        }

        return redirect('/inventario')->with('success', 'Stock actualizado correctamente.');
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

        $nombresProductos = $productos->pluck('nombre')->filter()->unique()->sort()->values();
        // El picker de tallas necesita saber de entrada a qué producto
        // pertenece la talla ya guardada, para abrir con ese producto
        // seleccionado en vez de mostrar el picker vacío.
        $nombreProductoActual = optional(
            $productos->first(fn ($p) => data_get($p, 'id') == data_get($inventario, 'producto_id'))
        )->nombre;

        return view('inventario/edicion', compact('inventario', 'sucursales', 'productos', 'nombresProductos', 'nombreProductoActual'));
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