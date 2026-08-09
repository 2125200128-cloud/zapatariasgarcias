@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-3xl rounded-lg bg-white p-6 shadow">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Nuevo pedido</h1>
            <p class="text-sm text-gray-500">Registra un nuevo pedido en la API de ZAPATERIA_API.</p>
        </div>
        <a href="{{ url('/pedido') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Volver</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/pedido/guardar') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sucursal que solicita</label>
                @if ($sucursalFija ?? null)
                    <input type="text" value="{{ data_get($sucursalFija, 'nombre', '—') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-50" disabled>
                    <input type="hidden" name="sucursal_id" value="{{ data_get($sucursalFija, 'id', '') }}">
                @else
                    <select name="sucursal_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                        <option value="">-- Selecciona --</option>
                        @foreach ($sucursales ?? [] as $sucursal)
                            <option value="{{ data_get($sucursal, 'id', '') }}" {{ old('sucursal_id') == data_get($sucursal, 'id', '') ? 'selected' : '' }}>
                                {{ data_get($sucursal, 'nombre', 'Sucursal') }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>
        </div>

        <h2 class="text-sm font-semibold text-gray-700 mb-2">Productos solicitados</h2>

        <div id="filasProductos" class="space-y-2 mb-2">
            <div class="fila-producto flex gap-2">
                <select name="producto_id[]" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="">-- Producto --</option>
                    @foreach ($productos ?? [] as $producto)
                        <option value="{{ data_get($producto, 'id', '') }}">
                            {{ data_get($producto, 'nombre', 'Producto') }} ({{ data_get($producto, 'talla', '—') }}) — disponible: {{ $stockMatriz[data_get($producto, 'id', '')] ?? 0 }}
                        </option>
                    @endforeach
                </select>
                <input type="number" name="cantidad[]" min="1" placeholder="Cantidad" class="w-32 border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <button type="button" class="quitar-fila px-3 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50">×</button>
            </div>
        </div>

        <button type="button" id="agregarProducto" class="text-blue-600 text-sm hover:underline mb-6">+ Agregar producto</button>

        <p class="text-xs text-gray-500 mb-4">
            Un encargado de la matriz revisará este pedido, lo aceptará y asignará chofer y unidad para la entrega.
        </p>

        <div class="flex justify-end gap-3">
            <a href="{{ url('/pedido') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const contenedor = document.getElementById('filasProductos');

    function activarQuitar(fila) {
        fila.querySelector('.quitar-fila').addEventListener('click', () => {
            if (contenedor.querySelectorAll('.fila-producto').length > 1) {
                fila.remove();
            }
        });
    }

    contenedor.querySelectorAll('.fila-producto').forEach(activarQuitar);

    document.getElementById('agregarProducto').addEventListener('click', () => {
        const primera = contenedor.querySelector('.fila-producto');
        const nueva = primera.cloneNode(true);
        nueva.querySelectorAll('select, input').forEach(el => el.value = '');
        activarQuitar(nueva);
        contenedor.appendChild(nueva);
    });
});
</script>

@endsection
