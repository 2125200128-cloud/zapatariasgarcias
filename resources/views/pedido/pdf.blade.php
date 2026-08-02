<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nota de Pedido #{{ $pedido->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 18px; margin-bottom: 0; }
        .subtitulo { color: #555; margin-top: 2px; margin-bottom: 20px; }
        table.datos { width: 100%; margin-bottom: 20px; }
        table.datos td { padding: 4px 0; }
        table.datos td.etiqueta { color: #555; width: 120px; }
        table.productos { width: 100%; border-collapse: collapse; }
        table.productos th { text-align: left; background: #f0f0f0; padding: 6px 8px; border-bottom: 2px solid #ccc; }
        table.productos td { padding: 6px 8px; border-bottom: 1px solid #eee; }
        table.productos td.numero { text-align: right; }
        tfoot td { padding: 8px; font-weight: bold; border-top: 2px solid #ccc; }
    </style>
</head>
<body>
    <h1>Zapatería Hermanos García</h1>
    <p class="subtitulo">Nota de Pedido #{{ $pedido->id }}</p>

    <table class="datos">
        <tr>
            <td class="etiqueta">Sucursal:</td>
            <td>{{ $sucursalActual->nombre ?? 'Sin sucursal asociada' }}</td>
        </tr>
        <tr>
            <td class="etiqueta">Fecha:</td>
            <td>{{ $pedido->fecha }}</td>
        </tr>
        <tr>
            <td class="etiqueta">Estatus:</td>
            <td>{{ $pedido->estatus }}</td>
        </tr>
    </table>

    <table class="productos">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Talla</th>
                <th class="numero">Cantidad</th>
                <th class="numero">Precio</th>
                <th class="numero">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse ($pedido->detallePedidos as $detalle)
                @php
                    $subtotal = $detalle->cantidad_solicitada * $detalle->precio;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $detalle->producto->nombre ?? 'Producto eliminado' }}</td>
                    <td>{{ $detalle->producto->talla ?? '—' }}</td>
                    <td class="numero">{{ $detalle->cantidad_solicitada }}</td>
                    <td class="numero">${{ number_format($detalle->precio, 2) }}</td>
                    <td class="numero">${{ number_format($subtotal, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Este pedido no tiene productos registrados.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right;">Total</td>
                <td class="numero">${{ number_format($total, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
