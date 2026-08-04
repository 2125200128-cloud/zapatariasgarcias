<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Empleado;
use App\Models\Pedido;
use App\Models\Sucursal;
use App\Models\Producto;
use App\Models\Detalle_pedido;
use App\Models\Inventario;
use App\Models\Chofer;
use App\Models\Carro;
use App\Models\Trayecto;
use Barryvdh\DomPDF\Facade\Pdf;

class PedidoController extends Controller
{
    // Un Encargado de sucursal (que no sea de la matriz) solo ve/toca sus
    // propios pedidos; Administrador y el Encargado de la matriz ven todo.
    private function puedeAsignar(): bool
    {
        $empleado = Empleado::auth();
        return $empleado !== null && ($empleado->esAdministrador() || $empleado->esMatriz());
    }

    private function autorizarPedido(Pedido $pedido)
    {
        if ($this->puedeAsignar()) {
            return;
        }
        $miSucursal = Empleado::auth()?->miSucursal();
        if (!$miSucursal || $pedido->empleado_id !== $miSucursal->empleado_id) {
            abort(403, 'No tienes acceso a este pedido.');
        }
    }

    public function inicio()
    {
        $query = Pedido::with(['empleado.sucursales', 'detallePedidos', 'trayectos']);

        if (!$this->puedeAsignar()) {
            $miSucursal = Empleado::auth()?->miSucursal();
            $query->where('empleado_id', $miSucursal?->empleado_id ?? 0);
        }

        $pedidos = $query->get();

        return view('pedido/inicio', compact('pedidos'));
    }

    public function formulario()
    {
        $empleado = Empleado::auth();
        $puedeElegirSucursal = $this->puedeAsignar();

        $sucursales = $puedeElegirSucursal ? Sucursal::all() : collect();
        $sucursalFija = $puedeElegirSucursal || !$empleado ? null : $empleado->miSucursal();

        $productos = Producto::all();

        $matriz = Sucursal::matriz();
        $stockMatriz = $matriz
            ? Inventario::where('sucursal_id', $matriz->id)->pluck('stock', 'producto_id')
            : collect();

        return view('pedido/formulario', compact('sucursales', 'sucursalFija', 'productos', 'stockMatriz'));
    }

    public function editar(Request $request)
    {
        if (!$this->puedeAsignar()) {
            abort(403, 'Solo la matriz puede editar pedidos.');
        }

        $id = $request->route('id');
        $pedido = Pedido::with('detallePedidos.producto')->find($id);
        if (!$pedido) {
            return redirect('/pedido')->with('error', 'Pedido no encontrado');
        }

        $sucursales = Sucursal::all();
        $productos = Producto::all();
        $sucursalActual = optional($pedido->empleado)->sucursales->first();

        return view('pedido/edicion', compact('pedido', 'sucursales', 'productos', 'sucursalActual'));
    }

    // Lee producto_id[]/cantidad[] del formulario una sola vez (filas vacías
    // o con producto inexistente se ignoran). Se reusa tanto para validar
    // stock como para crear los detalles, así ambos ven exactamente la
    // misma lista.
    private function detallesSolicitados(Request $request)
    {
        $productosIds = $request->input('producto_id', []);
        $cantidades = $request->input('cantidad', []);
        $detalles = [];

        foreach ($productosIds as $i => $productoId) {
            if (!$productoId || !($cantidades[$i] ?? null)) {
                continue;
            }
            $producto = Producto::find($productoId);
            if (!$producto) {
                continue;
            }
            $detalles[] = ['producto' => $producto, 'cantidad' => (int) $cantidades[$i]];
        }

        return $detalles;
    }

    private function guardarDetalles(Pedido $pedido, array $detallesSolicitados)
    {
        foreach ($detallesSolicitados as $item) {
            Detalle_pedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $item['producto']->id,
                'precio' => $item['producto']->precio,
                'cantidad_solicitada' => $item['cantidad'],
            ]);
        }
    }

    public function actualizar(Request $request)
    {
        if (!$this->puedeAsignar()) {
            abort(403, 'Solo la matriz puede editar pedidos.');
        }

        $id = $request->route('id');
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return redirect('/pedido')->with('error', 'Pedido no encontrado');
        }

        $sucursal = Sucursal::find($request->input('sucursal_id'));
        if (!$sucursal) {
            return redirect('/pedido')->with('error', 'Selecciona una sucursal válida');
        }
        $pedido->empleado_id = $sucursal->empleado_id;
        $pedido->estatus = $request->input('estatus');
        $pedido->save();

        $pedido->detallePedidos()->delete();
        $this->guardarDetalles($pedido, $this->detallesSolicitados($request));

        return redirect('/pedido')->with('success', 'Pedido actualizado');
    }

    public function guardar(Request $request)
    {
        $empleado = Empleado::auth();

        if ($this->puedeAsignar()) {
            $sucursal = Sucursal::find($request->input('sucursal_id'));
        } else {
            // Un encargado de sucursal solo pide para la suya — se ignora
            // cualquier sucursal_id que venga del formulario.
            $sucursal = $empleado?->miSucursal();
        }

        if (!$sucursal) {
            return redirect('/pedido/formulario')->with('error', 'Selecciona una sucursal válida');
        }

        $detallesSolicitados = $this->detallesSolicitados($request);
        $matriz = Sucursal::matriz();
        $erroresStock = [];

        if (!$matriz) {
            $erroresStock[] = 'No se ha configurado la sucursal matriz; no se puede validar el inventario.';
        } else {
            foreach ($detallesSolicitados as $item) {
                $stockDisponible = (int) (Inventario::where('sucursal_id', $matriz->id)
                    ->where('producto_id', $item['producto']->id)
                    ->value('stock') ?? 0);

                if ($item['cantidad'] > $stockDisponible) {
                    $erroresStock[] = "Stock insuficiente de {$item['producto']->nombre} ({$item['producto']->talla}): "
                        . "disponible {$stockDisponible}, solicitado {$item['cantidad']}.";
                }
            }
        }

        if (!empty($erroresStock)) {
            return redirect('/pedido/formulario')->withErrors($erroresStock)->withInput();
        }

        // Todo o nada: si algo falla a medio camino (pedido, detalles o
        // descuento de stock), no debe quedar nada a medias. Ya no se asigna
        // chofer/carro aquí — eso ahora lo hace la matriz al aceptar el
        // pedido (ver pendientes()/aceptar()).
        DB::transaction(function () use ($sucursal, $request, $detallesSolicitados, $matriz) {
            $pedido = new Pedido();
            $pedido->empleado_id = $sucursal->empleado_id;
            $pedido->estatus = 'Pendiente';
            $pedido->save();

            $this->guardarDetalles($pedido, $detallesSolicitados);

            foreach ($detallesSolicitados as $item) {
                Inventario::where('sucursal_id', $matriz->id)
                    ->where('producto_id', $item['producto']->id)
                    ->decrement('stock', $item['cantidad']);
            }
        });

        return redirect('/pedido')->with('success', 'Pedido guardado. Un encargado de la matriz lo aceptará y asignará la entrega.');
    }

    // Pedidos que ya se pueden aceptar: siguen Pendiente y todavía no
    // tienen ningún trayecto asignado.
    public function pendientes()
    {
        if (!$this->puedeAsignar()) {
            abort(403, 'Solo la matriz puede aceptar pedidos.');
        }

        $pedidos = Pedido::where('estatus', 'Pendiente')
            ->whereDoesntHave('trayectos')
            ->with(['empleado.sucursales', 'detallePedidos.producto'])
            ->get();

        return view('pedido/pendientes', compact('pedidos'));
    }

    public function aceptarFormulario(Request $request)
    {
        if (!$this->puedeAsignar()) {
            abort(403, 'Solo la matriz puede aceptar pedidos.');
        }

        $id = $request->route('id');
        $pedido = Pedido::with(['empleado.sucursales', 'detallePedidos.producto'])->find($id);
        if (!$pedido) {
            return redirect('/pedido/pendientes')->with('error', 'Pedido no encontrado');
        }

        $choferes = Chofer::disponible()->get();
        $carros = Carro::disponible()->get();

        return view('pedido/aceptar', compact('pedido', 'choferes', 'carros'));
    }

    public function aceptar(Request $request)
    {
        if (!$this->puedeAsignar()) {
            abort(403, 'Solo la matriz puede aceptar pedidos.');
        }

        $id = $request->route('id');
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return redirect('/pedido/pendientes')->with('error', 'Pedido no encontrado');
        }

        $choferId = $request->input('chofer_id');
        $carroId = $request->input('carro_id');
        $choferValido = $choferId && Chofer::disponible()->whereKey($choferId)->exists();
        $carroValido = $carroId && Carro::disponible()->whereKey($carroId)->exists();

        if (!$choferValido || !$carroValido) {
            return redirect('/pedido/' . $id . '/aceptar')->with('error', 'Elige un chofer y un carro disponibles.');
        }

        $sucursalDestino = optional($pedido->empleado)->sucursales->first();

        $trayecto = Trayecto::create([
            'chofer_id' => $choferId,
            'carro_id' => $carroId,
            'pedido_id' => $pedido->id,
            'estatus' => 'Aceptado',
            'descripcion_ruta' => 'Entrega de calzado a ' . ($sucursalDestino->nombre ?? 'sucursal'),
        ]);

        return redirect('/pedido/pendientes')->with('success', 'Pedido aceptado y entrega asignada (trayecto #' . $trayecto->id . ').');
    }

    public function eliminar(Request $request)
    {
        if (!$this->puedeAsignar()) {
            abort(403, 'Solo la matriz puede cancelar pedidos.');
        }

        $id = $request->route('id');
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return redirect('/pedido')->with('error', 'Pedido no encontrado');
        }

        $pedido->estatus = 'Cancelado';
        $pedido->save();
        return redirect('/pedido')->with('success', 'Pedido cancelado');
    }

    public function mostrar(Request $request)
    {
        if (!$this->puedeAsignar()) {
            abort(403, 'Solo la matriz puede cancelar pedidos.');
        }

        $id = $request->route('id');
        $pedido = Pedido::find($id);
        if (!$pedido) {
            return redirect('/pedido')->with('error', 'Pedido no encontrado');
        }

        $sucursalActual = optional($pedido->empleado)->sucursales->first();

        return view('pedido/borrado', compact('pedido', 'sucursalActual'));
    }

    public function pdf(Request $request)
    {
        $id = $request->route('id');
        $pedido = Pedido::with('detallePedidos.producto')->find($id);
        if (!$pedido) {
            return redirect('/pedido')->with('error', 'Pedido no encontrado');
        }
        $this->autorizarPedido($pedido);

        $sucursalActual = optional($pedido->empleado)->sucursales->first();

        $pdf = Pdf::loadView('pedido/pdf', compact('pedido', 'sucursalActual'));

        return $pdf->stream('nota-pedido-' . $pedido->id . '.pdf');
    }
}
