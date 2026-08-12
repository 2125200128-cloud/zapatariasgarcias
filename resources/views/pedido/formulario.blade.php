@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-3xl rounded-lg bg-white p-6 shadow">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Nuevo pedido</h1>
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

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Producto</label>
            <select id="selectorProducto" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="">-- Elige un producto --</option>
                @foreach ($nombresProductos ?? [] as $nombre)
                    <option value="{{ $nombre }}">{{ $nombre }}</option>
                @endforeach
            </select>
        </div>

        <div id="tallasProducto" class="mb-4 hidden">
            <label class="block text-sm font-medium text-gray-700 mb-1">Talla</label>
            <div id="chipsTallas" class="flex flex-wrap gap-2"></div>
        </div>

        <div id="carritoVacio" class="text-sm text-gray-400 italic mb-4">Todavía no has agregado ningún producto.</div>

        <div id="filasCarrito" class="space-y-2 mb-6"></div>

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
    // Catálogo agrupado por nombre de producto, cada uno con sus tallas y
    // disponibilidad en matriz — así el selector de arriba solo lista
    // productos (no una fila por cada talla).
    const stockMatriz = @json($stockMatriz ?? []);
    const productos = @json($productos ?? []);

    const porNombre = {};
    productos.forEach((p) => {
        const nombre = p.nombre || 'Producto';
        if (!porNombre[nombre]) porNombre[nombre] = [];
        porNombre[nombre].push({
            id: p.id,
            talla: p.talla ?? '—',
            disponible: stockMatriz[p.id] ?? 0,
        });
    });

    const selectorProducto = document.getElementById('selectorProducto');
    const bloqueTallas = document.getElementById('tallasProducto');
    const chipsTallas = document.getElementById('chipsTallas');
    const filasCarrito = document.getElementById('filasCarrito');
    const carritoVacio = document.getElementById('carritoVacio');

    // producto_id -> { nombre, talla, disponible, cantidad }
    const carrito = {};

    function renderCarrito() {
        filasCarrito.innerHTML = '';
        const ids = Object.keys(carrito);
        carritoVacio.classList.toggle('hidden', ids.length > 0);

        ids.forEach((id) => {
            const item = carrito[id];
            const fila = document.createElement('div');
            fila.className = 'flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2';
            fila.innerHTML = `
                <span class="flex-1 text-sm text-gray-700">${item.nombre} <span class="text-gray-400">— talla ${item.talla}</span></span>
                <input type="hidden" name="producto_id[]" value="${id}">
                <input type="number" name="cantidad[]" min="1" max="${item.disponible}" value="${item.cantidad}"
                    class="w-24 border border-gray-300 rounded-lg px-2 py-1.5 text-sm cantidad-carrito" data-id="${id}">
                <button type="button" class="quitar-carrito px-3 rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-50" data-id="${id}">×</button>
            `;
            filasCarrito.appendChild(fila);
        });

        filasCarrito.querySelectorAll('.cantidad-carrito').forEach((input) => {
            input.addEventListener('input', () => {
                carrito[input.dataset.id].cantidad = input.value;
            });
        });

        filasCarrito.querySelectorAll('.quitar-carrito').forEach((boton) => {
            boton.addEventListener('click', () => {
                delete carrito[boton.dataset.id];
                renderCarrito();
                renderChips();
            });
        });
    }

    function renderChips() {
        const nombre = selectorProducto.value;
        chipsTallas.innerHTML = '';
        bloqueTallas.classList.toggle('hidden', !nombre);
        if (!nombre) return;

        (porNombre[nombre] || []).forEach((variante) => {
            const sinStock = Number(variante.disponible) <= 0;
            const seleccionada = !!carrito[variante.id];

            const chip = document.createElement('button');
            chip.type = 'button';
            chip.textContent = `${variante.talla}${sinStock ? ' · sin stock' : ''}`;
            chip.disabled = sinStock;

            let clases = 'px-3 py-1.5 rounded-lg text-sm border ';
            if (sinStock) {
                clases += 'border-gray-200 bg-gray-50 text-gray-300 cursor-not-allowed line-through';
            } else if (seleccionada) {
                clases += 'border-blue-600 bg-blue-600 text-white';
            } else {
                clases += 'border-gray-300 text-gray-700 hover:border-blue-400 hover:bg-blue-50';
            }
            chip.className = clases;

            if (!sinStock) {
                chip.addEventListener('click', () => {
                    if (carrito[variante.id]) {
                        delete carrito[variante.id];
                    } else {
                        carrito[variante.id] = {
                            nombre,
                            talla: variante.talla,
                            disponible: variante.disponible,
                            cantidad: 1,
                        };
                    }
                    renderCarrito();
                    renderChips();
                });
            }

            chipsTallas.appendChild(chip);
        });
    }

    selectorProducto.addEventListener('change', renderChips);

    renderCarrito();
});
</script>

@endsection
