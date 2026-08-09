<?php

namespace App\Http\Controllers;

use RuntimeException;

class InicioController extends ApiFrontController
{
    public function inicio()
    {
        if (!$this->currentUser()) {
            return redirect('/login');
        }

        try {
            $payload = $this->client()->get('/api/inicio', $this->token());
        } catch (RuntimeException $exception) {
            return redirect('/login')->withErrors(['usuario' => $exception->getMessage()]);
        }

        $esMatrizOAdmin = (bool) ($payload['esMatrizOAdmin'] ?? false);
        $kpis = $payload['kpis'] ?? [];
        $grafico1Labels = $this->normalizeCollection($payload['grafico1Labels'] ?? []);
        $grafico1Datos = $this->normalizeCollection($payload['grafico1Datos'] ?? []);
        $topProductos = $this->normalizeCollection($payload['topProductos'] ?? []);
        $pedidosPorEstatus = $this->normalizeCollection($payload['pedidosPorEstatus'] ?? []);
        $trayectoActivoResumen = $this->normalizePayload($payload['trayectoActivoResumen'] ?? null);

        return view('/inicio', compact(
            'esMatrizOAdmin', 'kpis', 'grafico1Labels', 'grafico1Datos',
            'topProductos', 'pedidosPorEstatus', 'trayectoActivoResumen'
        ));
    }

    public function login()
    {
        return view('/login/inicio');
    }
}