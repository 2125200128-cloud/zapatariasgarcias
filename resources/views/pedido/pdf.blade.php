@php
    $estatus = data_get($pedido, 'estatus', '—');
    $colores = [
        'Realizado' => '#3f7a4f',
        'Entregado' => '#3f7a4f',
        'En ruta' => '#a4720c',
        'Aceptado' => '#a4720c',
        'Esperando aceptación' => '#a4720c',
        'Pendiente' => '#715b49',
        'Cancelado' => '#a13a3a',
    ];
    $colorEstatus = $colores[$estatus] ?? '#715b49';

    $fecha = data_get($pedido, 'fecha');
    try {
        $fecha = $fecha ? \Carbon\Carbon::parse($fecha)->locale('es')->translatedFormat('d \d\e F, Y - H:i') : '—';
    } catch (\Throwable $e) {
        $fecha = data_get($pedido, 'fecha', '—');
    }

    $solicitante = trim(data_get($pedido, 'empleado.nombre', '') . ' ' . data_get($pedido, 'empleado.apellido_paterno', ''));
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nota de Pedido #{{ data_get($pedido, 'id') }}</title>
    <style>
        @page { margin: 30px 40px; }

        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #3a2a20; }

        table { border-collapse: collapse; }

        .encabezado { width: 100%; border-bottom: 3px solid #4e3124; padding-bottom: 12px; margin-bottom: 18px; }
        .encabezado td { border: none; padding: 0; vertical-align: middle; }
        .logo { height: 46px; }
        .titulo-nota { text-align: right; }
        .titulo-nota h1 { margin: 0; font-size: 20px; color: #4e3124; letter-spacing: 1px; }
        .titulo-nota p { margin: 3px 0 0; font-size: 11px; color: #715b49; }

        .info-box { width: 100%; background: #ebe2d6; border-radius: 6px; padding: 14px 18px; margin-bottom: 20px; }
        .info-box td { border: none; padding: 2px 0; vertical-align: top; }
        .info-label { color: #715b49; text-transform: uppercase; font-size: 9px; letter-spacing: .5px; }
        .info-valor { color: #3a2a20; font-weight: bold; font-size: 12px; margin-top: 1px; }

        .estatus-badge {
            display: inline-block; margin-top: 3px; padding: 3px 12px; border-radius: 10px;
            font-size: 10px; font-weight: bold; color: #fff; background: {{ $colorEstatus }};
        }

        table.productos { width: 100%; margin-top: 4px; }
        table.productos th {
            background: #4e3124; color: #fff; font-size: 10px; text-transform: uppercase;
            letter-spacing: .3px; padding: 8px 7px; text-align: left;
        }
        table.productos td { padding: 7px; font-size: 11px; border-bottom: 1px solid #ebe2d6; }
        table.productos tr:nth-child(even) td { background: #faf8f5; }
        .num { text-align: right; }

        table.totales { width: 100%; margin-top: 8px; }
        table.totales td { border: none; padding: 4px 7px; font-size: 12px; }
        .total-final td { border-top: 2px solid #4e3124; font-size: 14px; font-weight: bold; color: #4e3124; padding-top: 10px; }

        .pie { margin-top: 40px; border-top: 1px solid #b0a290; padding-top: 10px; font-size: 9px; color: #715b49; text-align: center; }
    </style>
</head>
<body>

    <table class="encabezado">
        <tr>
            <td style="width: 55%;">
                <img class="logo" src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/Logo-negro.png'))) }}">
            </td>
            <td class="titulo-nota" style="width: 45%;">
                <h1>NOTA DE PEDIDO</h1>
                <p>Folio #{{ str_pad(data_get($pedido, 'id', 0), 5, '0', STR_PAD_LEFT) }}</p>
            </td>
        </tr>
    </table>

    <table class="info-box">
        <tr>
            <td style="width: 60%;">
                <div class="info-label">Sucursal</div>
                <div class="info-valor">{{ data_get($sucursalActual, 'nombre', '—') }}</div>

                <div class="info-label" style="margin-top: 10px;">Solicitado por</div>
                <div class="info-valor">{{ $solicitante ?: '—' }}</div>
            </td>
            <td style="width: 40%; text-align: right;">
                <div class="info-label">Fecha</div>
                <div class="info-valor">{{ $fecha }}</div>

                <div class="info-label" style="margin-top: 10px;">Estatus</div>
                <span class="estatus-badge">{{ $estatus }}</span>
            </td>
        </tr>
    </table>

    <table class="productos">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Talla</th>
                <th class="num">Cantidad</th>
                <th class="num">Precio unit.</th>
                <th class="num">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse (data_get($pedido, 'detalle_pedidos', []) as $detalle)
                @php
                    $cantidad = data_get($detalle, 'cantidad_solicitada', 0);
                    $precio = data_get($detalle, 'precio', 0);
                    $subtotal = $cantidad * $precio;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ data_get($detalle, 'producto.nombre', '—') }}</td>
                    <td>{{ data_get($detalle, 'producto.talla', '—') }}</td>
                    <td class="num">{{ $cantidad }}</td>
                    <td class="num">${{ number_format($precio, 2) }}</td>
                    <td class="num">${{ number_format($subtotal, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #b0a290; padding: 14px;">Sin productos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <table class="totales">
        <tr class="total-final">
            <td class="num" style="width: 82%;">Total</td>
            <td class="num" style="width: 18%;">${{ number_format($total, 2) }}</td>
        </tr>
    </table>

    <div class="pie">
        Zapatería Hermanos García &middot; Calzado que deja huella<br>
        Documento generado automáticamente el {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
