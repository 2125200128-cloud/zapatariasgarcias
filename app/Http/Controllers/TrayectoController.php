<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RuntimeException;

class TrayectoController extends ApiFrontController
{
    public function listado()
    {
        try {
            $payload = $this->client()->get('/api/trayectos', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/login')->withErrors(['usuario' => $exception->getMessage()]);
        }

        $trayectos = $this->normalizeCollection($payload['trayectos'] ?? []);
        $puedeGestionar = $this->puedeGestionar();

        return view('trayecto/lista', compact('trayectos', 'puedeGestionar'));
    }

    public function flota()
    {
        return view('trayecto/flota');
    }

    public function ubicaciones()
    {
        try {
            $payload = $this->client()->get('/api/trayectos/fleet-locations', $this->token());
        } catch (RuntimeException $exception) {
            return response()->json(['error' => $exception->getMessage()], 500);
        }

        return response()->json($payload);
    }

    // No hay formulario()/guardar(): la API no tiene POST /api/trayectos.
    // Un trayecto solo se crea al aceptar un pedido pendiente
    // (PedidoController::aceptar()/procesarAceptar()).

    public function editar(string $id)
    {
        if (!$this->puedeGestionar()) {
            abort(403, 'Solo la matriz puede editar trayectos.');
        }

        try {
            $payload = $this->client()->get("/api/trayectos/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/trayecto/lista')->withErrors(['trayecto' => $exception->getMessage()]);
        }

        $trayecto = $this->normalizePayload($payload['trayecto'] ?? null);
        $choferes = $this->normalizeCollection($payload['choferes'] ?? []);
        $carros = $this->normalizeCollection($payload['carros'] ?? []);
        $pedidos = $this->normalizeCollection($payload['pedidos'] ?? []);

        return view('trayecto/edicion', compact('trayecto', 'choferes', 'carros', 'pedidos'));
    }

    public function actualizar(Request $request, string $id)
    {
        if (!$this->puedeGestionar()) {
            abort(403, 'Solo la matriz puede editar trayectos.');
        }

        $data = [
            'pedido_id' => $request->input('pedido_id'),
            'chofer_id' => $request->input('chofer_id'),
            'carro_id' => $request->input('carro_id'),
            'estatus' => $request->input('estatus'),
            'descripcion_ruta' => $request->input('descripcion_ruta'),
        ];

        try {
            $this->client()->put("/api/trayectos/{$id}", $data, $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['trayecto' => $exception->getMessage()])->withInput();
        }

        return redirect('/trayecto/lista')->with('success', 'Trayecto actualizado correctamente.');
    }

    // Reusa el mismo endpoint que editar(): la API no tiene una ruta propia
    // para "mostrar" un trayecto — mismo patrón que ya vimos con pedidos.
    public function mostrar(string $id)
    {
        if (!$this->puedeGestionar()) {
            abort(403, 'Solo la matriz puede cancelar trayectos.');
        }

        try {
            $payload = $this->client()->get("/api/trayectos/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/trayecto/lista')->withErrors(['trayecto' => $exception->getMessage()]);
        }

        $trayecto = $this->normalizePayload($payload['trayecto'] ?? null);

        return view('trayecto/borrado', compact('trayecto'));
    }

    public function eliminar(string $id)
    {
        if (!$this->puedeGestionar()) {
            abort(403, 'Solo la matriz puede cancelar trayectos.');
        }

        try {
            $payload = $this->client()->delete("/api/trayectos/{$id}", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/trayecto/lista')->withErrors(['trayecto' => $exception->getMessage()]);
        }

        return redirect('/trayecto/lista')->with('success', $payload['message'] ?? 'Trayecto cancelado correctamente.');
    }

    public function compartir(string $id)
    {
        try {
            $payload = $this->client()->get("/api/trayectos/{$id}/share", $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/trayecto/lista')->withErrors(['trayecto' => $exception->getMessage()]);
        }

        $trayecto = $this->normalizePayload($payload['trayecto'] ?? null);
        $urlUbicacion = $payload['urlUbicacion'] ?? null;
        $yaTermino = (bool) ($payload['yaTermino'] ?? false);

        return view('trayecto/compartir', compact('trayecto', 'urlUbicacion', 'yaTermino'));
    }

    // Nuevo: no existía ni ruta ni método. Cualquier empleado autorizado
    // (encargado del destino o admin — la API ya lo valida) puede confirmar
    // que su pedido llegó.
    public function confirmarLlegada(string $id)
    {
        try {
            $payload = $this->client()->post("/api/trayectos/{$id}/confirm-arrival", [], $this->token());
        } catch (RuntimeException $exception) {
            return back()->withErrors(['trayecto' => $exception->getMessage()]);
        }

        return back()->with('success', $payload['message'] ?? 'Entrega confirmada.');
    }
}