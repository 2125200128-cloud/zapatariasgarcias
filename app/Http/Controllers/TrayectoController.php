<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trayecto;
use App\Models\TrayectoUbicacion;

class TrayectoController extends Controller
{
    //
    public function inicio()
    {
        return view('trayecto/inicio');
    }

    public function listado()
    {
        $trayectos = Trayecto::with(['chofer', 'carro', 'pedido'])->get();

        return view('trayecto/lista', compact('trayectos'));
    }

    public function flota()
    {
        return view('trayecto/flota');
    }

    public function ubicacionesFlota()
    {
        $trayectos = Trayecto::with(['chofer', 'pedido.empleado.sucursales', 'ubicacionActual'])
            ->whereNotIn('estatus', ['Entregado', 'Cancelado'])
            ->get();

        $municipios = config('ubicaciones.municipios');

        $datos = $trayectos->map(function (Trayecto $trayecto) use ($municipios) {
            $sucursal = null;
            if ($trayecto->pedido && $trayecto->pedido->empleado) {
                $sucursal = $trayecto->pedido->empleado->sucursales->first();
            }

            $destino = null;
            if ($sucursal && isset($municipios[$sucursal->municipio])) {
                [$lat, $lng] = $municipios[$sucursal->municipio];
                $destino = [
                    'sucursal' => $sucursal->nombre,
                    'municipio' => $sucursal->municipio,
                    'lat' => $lat,
                    'lng' => $lng,
                ];
            }

            $posicion = null;
            if ($trayecto->ubicacionActual) {
                $posicion = [
                    'lat' => (float) $trayecto->ubicacionActual->latitud,
                    'lng' => (float) $trayecto->ubicacionActual->longitud,
                    'actualizado_en' => $trayecto->ubicacionActual->registrado_en,
                ];
            }

            return [
                'trayecto_id' => $trayecto->id,
                'estatus' => $trayecto->estatus,
                'chofer' => $trayecto->chofer ? trim($trayecto->chofer->nombre . ' ' . $trayecto->chofer->apellido) : null,
                'pedido_id' => $trayecto->pedido_id,
                'destino' => $destino,
                'posicion' => $posicion,
            ];
        });

        return response()->json($datos->values());
    }

    public function compartirUbicacion(Request $request)
    {
        $id = $request->route('id');
        $trayecto = Trayecto::find($id);

        if (!$trayecto) {
            abort(404, 'Trayecto no encontrado');
        }

        return view('trayecto/compartir', ['trayecto' => $trayecto]);
    }

    public function registrarUbicacion(Request $request)
    {
        $id = $request->route('id');
        $trayecto = Trayecto::find($id);

        if (!$trayecto) {
            return response()->json(['error' => 'Trayecto no encontrado'], 404);
        }

        $validado = $request->validate([
            'latitud' => 'required|numeric|between:-90,90',
            'longitud' => 'required|numeric|between:-180,180',
        ]);

        TrayectoUbicacion::create([
            'trayecto_id' => $trayecto->id,
            'latitud' => $validado['latitud'],
            'longitud' => $validado['longitud'],
        ]);

        return response()->json(['ok' => true]);
    }
}
