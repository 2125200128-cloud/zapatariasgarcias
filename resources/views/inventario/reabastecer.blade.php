@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-3xl rounded-lg bg-white p-6 shadow">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Reabastecer inventario</h1>
            <p class="text-sm text-gray-500">Suma stock a varias tallas de la matriz de un jalón — ej. llegó un envío con varias tallas del mismo producto.</p>
        </div>
        <a href="{{ url('/inventario') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Volver</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ url('/inventario/reabastecer') }}" method="POST">
        @csrf

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
            <label class="block text-sm font-medium text-gray-700 mb-1">Tallas a reabastecer</label>
            <div id="chipsTallas" class="flex flex-wrap gap-2"></div>
        </div>

        <div id="carritoVacio" class="text-sm text-gray-400 italic mb-4">Todavía no has agregado ninguna talla.</div>

        <div id="filasCarrito" class="space-y-2 mb-6"></div>

        <div class="flex justify-end gap-3">
            <a href="{{ url('/inventario') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const stockMatriz = @json($stockMatriz ?? []);
    const productos = @json($productos ?? []);

    const porNombre = {};
    productos.forEach((p) => {
        const nombre = p.nombre || 'Producto';
        if (!porNombre[nombre]) porNombre[nombre] = [];
        porNombre[nombre].push({ id: p.id, talla: p.talla ?? '—', actual: stockMatriz[p.id] ?? 0 });
    });

    const selectorProducto = document.getElementById('selectorProducto');
    const bloqueTallas = document.getElementById('tallasProducto');
    const chipsTallas = document.getElementById('chipsTallas');
    const filasCarrito = document.getElementById('filasCarrito');
    const carritoVacio = document.getElementById('carritoVacio');

    // producto_id -> { nombre, talla, actual, cantidad }
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
                <span class="flex-1 text-sm text-gray-700">${item.nombre} <span class="text-gray-400">— talla ${item.talla} (tienes ${item.actual})</span></span>
                <input type="hidden" name="producto_id[]" value="${id}">
                <input type="number" name="cantidad[]" min="1" value="${item.cantidad}"
                    class="w-28 border border-gray-300 rounded-lg px-2 py-1.5 text-sm cantidad-carrito" data-id="${id}" placeholder="Cantidad">
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
            const seleccionada = !!carrito[variante.id];

            const chip = document.createElement('button');
            chip.type = 'button';
            chip.textContent = `${variante.talla} (tienes ${variante.actual})`;
            chip.className = 'px-3 py-1.5 rounded-lg text-sm border ' + (
                seleccionada
                    ? 'border-blue-600 bg-blue-600 text-white'
                    : 'border-gray-300 text-gray-700 hover:border-blue-400 hover:bg-blue-50'
            );

            chip.addEventListener('click', () => {
                if (carrito[variante.id]) {
                    delete carrito[variante.id];
                } else {
                    carrito[variante.id] = {
                        nombre,
                        talla: variante.talla,
                        actual: variante.actual,
                        cantidad: 1,
                    };
                }
                renderCarrito();
                renderChips();
            });

            chipsTallas.appendChild(chip);
        });
    }

    selectorProducto.addEventListener('change', renderChips);

    renderCarrito();
});
</script>

@endsection
