@extends('/plantilla/base')

@section('dinamico')

<h1 class="text-2xl font-semibold text-gray-800 mb-4">Editar pedido</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ url('/pedido/actualizar/' . $pedido->id) }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div>
                <label for="sucursal_id" class="block text-sm font-medium text-gray-700 mb-1">Sucursal que solicita</label>
                <select name="sucursal_id" id="sucursal_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Selecciona --</option>
                    @foreach ($sucursales ?? [] as $sucursal)
                        <option value="{{ $sucursal->id }}" @selected(optional($sucursalActual)->id == $sucursal->id)>{{ $sucursal->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="estatus" class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
                <select name="estatus" id="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Pendiente" @selected($pedido->estatus === 'Pendiente')>Pendiente</option>
                    <option value="Realizado" @selected($pedido->estatus === 'Realizado')>Realizado</option>
                    <option value="Cancelado" @selected($pedido->estatus === 'Cancelado')>Cancelado</option>
                </select>
            </div>
        </div>

        <h2 class="text-sm font-semibold text-gray-700 mb-2">Productos solicitados</h2>

        <div id="filasProductos" class="space-y-2 mb-2">
            @forelse ($pedido->detallePedidos as $detalle)
                <div class="fila-producto flex gap-2">
                    <select name="producto_id[]" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Producto --</option>
                        @foreach ($productos ?? [] as $producto)
                            <option value="{{ $producto->id }}" @selected($detalle->producto_id == $producto->id)>{{ $producto->nombre }} ({{ $producto->talla }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="cantidad[]" min="1" value="{{ $detalle->cantidad_solicitada }}" placeholder="Cantidad" class="w-32 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" class="quitar-fila px-3 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50">×</button>
                </div>
            @empty
                <div class="fila-producto flex gap-2">
                    <select name="producto_id[]" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Producto --</option>
                        @foreach ($productos ?? [] as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }} ({{ $producto->talla }})</option>
                        @endforeach
                    </select>
                    <input type="number" name="cantidad[]" min="1" placeholder="Cantidad" class="w-32 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="button" class="quitar-fila px-3 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50">×</button>
                </div>
            @endforelse
        </div>

        <button type="button" id="agregarProducto" class="text-blue-600 text-sm hover:underline mb-6">+ Agregar producto</button>

        <div>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar cambios</button>
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
