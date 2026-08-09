@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-3xl rounded-lg bg-white p-6 shadow">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Editar registro de inventario</h1>
            <p class="text-sm text-gray-500">Actualiza la información del inventario en la API.</p>
        </div>
        <a href="{{ url('/inventario') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Volver</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ url('/inventario/actualizar/' . data_get($inventario, 'id', '')) }}" method="POST" class="grid gap-4 sm:grid-cols-2">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Sucursal</label>
            <select name="sucursal_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                <option value="">-- Selecciona --</option>
                @foreach ($sucursales ?? [] as $sucursal)
                    <option value="{{ data_get($sucursal, 'id', '') }}" 
                        {{ old('sucursal_id', data_get($inventario, 'sucursal_id', '')) == data_get($sucursal, 'id', '') ? 'selected' : '' }}>
                        {{ data_get($sucursal, 'nombre', 'Sucursal') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
            <input type="text" id="productoBuscar" placeholder="Buscar por nombre, marca o talla..."
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mb-2">
            <select name="producto_id" id="producto_id" size="8"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                <option value="">-- Selecciona --</option>
                @foreach ($productos ?? [] as $producto)
                    <option value="{{ data_get($producto, 'id', '') }}"
                        data-buscar="{{ strtolower(data_get($producto, 'nombre', '') . ' ' . data_get($producto, 'marca.nombre', '') . ' ' . data_get($producto, 'talla', '')) }}"
                        {{ old('producto_id', data_get($inventario, 'producto_id', '')) == data_get($producto, 'id', '') ? 'selected' : '' }}>
                        {{ data_get($producto, 'nombre', 'Producto') }} — {{ data_get($producto, 'marca.nombre', 'Sin marca') }} (talla {{ data_get($producto, 'talla', '—') }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
            <input type="number" name="stock" value="{{ old('stock', data_get($inventario, 'stock', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="Activo" {{ old('estatus', data_get($inventario, 'estatus', '')) === 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Inactivo" {{ old('estatus', data_get($inventario, 'estatus', '')) === 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <div class="sm:col-span-2 flex justify-end gap-3">
            <a href="{{ url('/inventario') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar cambios</button>
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
            if (!opcion.value) return;
            opcion.style.display = opcion.dataset.buscar.includes(termino) ? '' : 'none';
        });
    });
});
</script>

@endsection
