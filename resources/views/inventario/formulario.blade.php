@extends('/plantilla/base')

@section('dinamico')

<h1 class="text-2xl font-semibold text-gray-800 mb-4">Nuevo registro de inventario</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ url('/inventario/guardar') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @csrf

        <div>
            <label for="sucursal_id" class="block text-sm font-medium text-gray-700 mb-1">Sucursal</label>
            <select name="sucursal_id" id="sucursal_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">-- Selecciona --</option>
                @foreach ($sucursales ?? [] as $sucursal)
                    <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="producto_id" class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
            <input type="text" id="productoBuscar" placeholder="Buscar por nombre, marca o talla..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="producto_id" id="producto_id" size="8"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">-- Selecciona --</option>
                @foreach ($productos ?? [] as $producto)
                    <option value="{{ $producto->id }}"
                        data-buscar="{{ strtolower($producto->nombre . ' ' . ($producto->marca->nombre ?? '') . ' ' . $producto->talla) }}">
                        {{ $producto->nombre }} — {{ $producto->marca->nombre ?? 'Sin marca' }} (talla {{ $producto->talla }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="stock" class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
            <input type="number" name="stock" id="stock" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="estatus" class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" id="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
            </select>
        </div>

        <div class="sm:col-span-2">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const buscador = document.getElementById('productoBuscar');
    const select = document.getElementById('producto_id');

    buscador.addEventListener('input', () => {
        const termino = buscador.value.trim().toLowerCase();
        Array.from(select.options).forEach(opcion => {
            if (!opcion.value) {
                return;
            }
            opcion.style.display = opcion.dataset.buscar.includes(termino) ? '' : 'none';
        });
    });
});
</script>

@endsection
