<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Models\Sucursal;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\Chofer;
use App\Models\Detalle_pedido;

class InicioController extends Controller
{
    public function inicio()
    {
        $empleado = Auth::guard('empleado')->user();
        $esMatrizOAdmin = $empleado->esAdministrador() || $empleado->esMatriz();

        if ($esMatrizOAdmin) {
            return $this->inicioMatriz();
        }

        return $this->inicioSucursal($empleado);
    }

    // Vista global: la matriz/administrador ve el panorama de todas las
    // sucursales.
    private function inicioMatriz()
    {
        $esMatrizOAdmin = true;

        $kpis = [
            'sucursales' => Sucursal::where('estatus', 'Activo')->count(),
            'pedidosPendientes' => Pedido::where('estatus', 'Pendiente')->count(),
            'productos' => Producto::where('estatus', 'Activo')->count(),
            'choferes' => Chofer::where('estatus', 'Activo')->count(),
        ];

        $grafico1Labels = Sucursal::all()->map(fn ($sucursal) => preg_replace('/^Sucursal\s+/i', '', $sucursal->nombre));
        $grafico1Datos = Sucursal::all()->map(fn ($sucursal) => Pedido::where('empleado_id', $sucursal->empleado_id)->count());

        $topProductos = Detalle_pedido::selectRaw('producto_id, SUM(cantidad_solicitada) as total')
            ->groupBy('producto_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('producto')
            ->get()
            ->filter(fn ($fila) => $fila->producto)
            ->map(fn ($fila) => ['nombre' => $fila->producto->nombre, 'total' => (int) $fila->total]);

        $pedidosPorEstatus = collect(['Pendiente', 'Realizado', 'Cancelado'])->map(function ($estatus) {
            return [
                'estatus' => $estatus,
                'total' => Pedido::where('estatus', $estatus)->count(),
            ];
        });

        return view('/inicio', compact('esMatrizOAdmin', 'kpis', 'grafico1Labels', 'grafico1Datos', 'topProductos', 'pedidosPorEstatus'));
    }

    // Vista de una sucursal: todo escalado a sus propios pedidos — nunca ve
    // datos de la matriz ni de las demás sucursales.
    private function inicioSucursal(Empleado $empleado)
    {
        $esMatrizOAdmin = false;

        $miSucursal = $empleado->miSucursal();
        $miEmpleadoId = optional($miSucursal)->empleado_id ?? 0;

        $kpis = [
            'pendientes' => Pedido::where('empleado_id', $miEmpleadoId)->where('estatus', 'Pendiente')->count(),
            'enCamino' => Pedido::where('empleado_id', $miEmpleadoId)
                ->whereHas('trayectos', fn ($q) => $q->whereIn('estatus', ['Aceptado', 'En ruta']))
                ->count(),
            'entregados' => Pedido::where('empleado_id', $miEmpleadoId)->where('estatus', 'Realizado')->count(),
            'productos' => Producto::where('estatus', 'Activo')->count(),
        ];

        $pedidosPorMes = Pedido::where('empleado_id', $miEmpleadoId)
            ->selectRaw("DATE_FORMAT(fecha, '%Y-%m') as mes, COUNT(*) as total")
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();
        $grafico1Labels = $pedidosPorMes->pluck('mes');
        $grafico1Datos = $pedidosPorMes->pluck('total');

        $topProductos = Detalle_pedido::selectRaw('producto_id, SUM(cantidad_solicitada) as total')
            ->whereHas('pedido', fn ($q) => $q->where('empleado_id', $miEmpleadoId))
            ->groupBy('producto_id')
            ->orderByDesc('total')
            ->take(5)
            ->with('producto')
            ->get()
            ->filter(fn ($fila) => $fila->producto)
            ->map(fn ($fila) => ['nombre' => $fila->producto->nombre, 'total' => (int) $fila->total]);

        $pedidosPorEstatus = collect(['Pendiente', 'Realizado', 'Cancelado'])->map(function ($estatus) use ($miEmpleadoId) {
            return [
                'estatus' => $estatus,
                'total' => Pedido::where('empleado_id', $miEmpleadoId)->where('estatus', $estatus)->count(),
            ];
        });

        return view('/inicio', compact('esMatrizOAdmin', 'kpis', 'grafico1Labels', 'grafico1Datos', 'topProductos', 'pedidosPorEstatus'));
    }

    public function login()
    {
        return view('/login/inicio');
    }
}
