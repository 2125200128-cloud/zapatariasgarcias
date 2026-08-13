@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-4xl mx-auto bg-cover bg-center p-8 rounded-lg" style="background-image: url('{{ asset('images/fondo-formulario.jpeg') }}')">
    <div class="max-w-3xl rounded-lg bg-white p-6 shadow">

        <form id="formInventario" action="{{ url('/inventario/guardar') }}" method="POST" class="grid gap-4 sm:grid-cols-2"></form>

            <div class="max-w-3xl rounded-lg bg-white p-6 shadow">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">Editar registro de inventario</h1>
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

                    @php
                        $sucursalDelRegistro = collect($sucursales ?? [])->first(
                            fn ($sucursal) => data_get($sucursal, 'id') == data_get($inventario, 'sucursal_id')
                        );
                        $nombreSucursal = data_get($sucursalDelRegistro, 'nombre', '—');
                    @endphp
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sucursal</label>
                        <input type="text" value="{{ $nombreSucursal }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-50" disabled>
                        <p class="text-xs text-gray-400 mt-1">La sucursal de un registro no se puede cambiar aquí — el inventario de una sucursal solo se actualiza cuando se le entrega un pedido.</p>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
                        <select id="selectorProducto" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            <option value="">-- Elige un producto --</option>
                            @foreach ($nombresProductos ?? [] as $nombre)
                                <option value="{{ $nombre }}" {{ old('nombre_producto', $nombreProductoActual ?? '') === $nombre ? 'selected' : '' }}>{{ $nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="tallasProducto" class="sm:col-span-2 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Talla</label>
                        <div id="chipsTallas" class="flex flex-wrap gap-2"></div>
                        <input type="hidden" name="producto_id" id="producto_id" value="{{ old('producto_id', data_get($inventario, 'producto_id', '')) }}" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                        <input type="number" name="stock" min="0" value="{{ old('stock', data_get($inventario, 'stock', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
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

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const productos = @json($productos ?? []);

    const porNombre = {};
    productos.forEach((p) => {
        const nombre = p.nombre || 'Producto';
        if (!porNombre[nombre]) porNombre[nombre] = [];
        porNombre[nombre].push({ id: p.id, talla: p.talla ?? '—' });
    });

    const selectorProducto = document.getElementById('selectorProducto');
    const bloqueTallas = document.getElementById('tallasProducto');
    const chipsTallas = document.getElementById('chipsTallas');
    const productoIdInput = document.getElementById('producto_id');

    function renderChips() {
        const nombre = selectorProducto.value;
        chipsTallas.innerHTML = '';
        bloqueTallas.classList.toggle('hidden', !nombre);
        if (!nombre) {
            productoIdInput.value = '';
            return;
        }

        (porNombre[nombre] || []).forEach((variante) => {
            const seleccionada = String(variante.id) === String(productoIdInput.value);

            const chip = document.createElement('button');
            chip.type = 'button';
            chip.textContent = variante.talla;
            chip.className = 'px-3 py-1.5 rounded-lg text-sm border ' + (
                seleccionada
                    ? 'border-blue-600 bg-blue-600 text-white'
                    : 'border-gray-300 text-gray-700 hover:border-blue-400 hover:bg-blue-50'
            );

            chip.addEventListener('click', () => {
                productoIdInput.value = variante.id;
                renderChips();
            });

            chipsTallas.appendChild(chip);
        });
    }

    selectorProducto.addEventListener('change', () => {
        productoIdInput.value = '';
        renderChips();
    });

    // Al cargar, si ya viene un producto elegido (edición normal, o un
    // error de validación que regresó con old()), se abre el picker con
    // ese producto y esa talla ya marcada — no arranca vacío.
    renderChips();

    document.querySelector('form').addEventListener('submit', (evento) => {
        if (!productoIdInput.value) {
            evento.preventDefault();
            alert('Elige un producto y una talla antes de guardar.');
        }
    });
});
</script>

@endsection
