<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nota de Pedido #{{ $pedido->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>Pedido #{{ $pedido->id }}</h2>
    <p>Sucursal: {{ data_get($sucursalActual, 'nombre', '—') }}</p>
    <p>Fecha: {{ data_get($pedido, 'fecha', '—') }}</p>
    <p>Estatus: {{ data_get($pedido, 'estatus', '—') }}</p>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Talla</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach (data_get($pedido, 'detallePedidos', []) as $detalle)
                <tr>
                    <td>{{ data_get($detalle, 'producto.nombre', '—') }}</td>
                    <td>{{ data_get($detalle, 'producto.talla', '—') }}</td>
                    <td>{{ data_get($detalle, 'cantidad_solicitada', '—') }}</td>
                    <td>${{ number_format(data_get($detalle, 'precio', 0), 2) }}</td>
                    <td>${{ number_format(data_get($detalle, 'cantidad_solicitada', 0) * data_get($detalle, 'precio', 0), 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
