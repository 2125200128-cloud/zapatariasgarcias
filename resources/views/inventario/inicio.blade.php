@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">{{ ($puedeGestionar ?? false) ? 'Inventario' : 'Mi inventario' }}</h1>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($inventarios ?? []) }} registros</span>
        @if ($puedeGestionar ?? false)
            <a href="{{ url('/inventario/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
                Nuevo registro
            </a>
        @endif
    </div>
</div>

@if (session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
        {{ $errors->first() }}
    </div>
@endif

{{-- Tabla Principal --}}
<div class="bg-white rounded-lg shadow-sm overflow-x-auto border border-gray-100">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">ID</th>
                @if ($puedeGestionar ?? false)
                    <th class="px-4 py-3">Sucursal</th>
                @endif
                <th class="px-4 py-3">Producto</th>
                <th class="px-4 py-3">Marca</th>
                <th class="px-4 py-3 text-center">Talla</th>
                <th class="px-4 py-3 text-center">Stock</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($inventarios ?? [] as $inventario)
                @php
                    // Mapeo seguro utilizando exactamente las relaciones de tu código original
                    $productoNombre = data_get($inventario, 'producto.nombre', '—');
                    $marcaNombre    = data_get($inventario, 'producto.marca.nombre', '—');
                    $talla          = data_get($inventario, 'producto.talla', '—');
                    $stock          = (int) data_get($inventario, 'stock', 0);
                    $sucursalNombre = data_get($inventario, 'sucursal.nombre', '—');
                    $estatus        = data_get($inventario, 'estatus', 'Activo');

                    // Paquete de datos para el Modal
                    $datosModal = [
                        'id'        => data_get($inventario, 'id', '—'),
                        'sucursal'  => $sucursalNombre,
                        'producto'  => $productoNombre,
                        'marca'     => $marcaNombre,
                        'talla'     => $talla,
                        'stock'     => $stock,
                        'estatus'   => $estatus,
                    ];
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors">
                    {{-- ID --}}
                    <td class="px-4 py-3 font-semibold text-gray-400">#{{ data_get($inventario, 'id', '—') }}</td>

                    {{-- Sucursal (Condicional) --}}
                    @if ($puedeGestionar ?? false)
                        <td class="px-4 py-3 font-medium text-gray-800">
                            <span class="inline-flex items-center gap-1.5">
                                <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                {{ $sucursalNombre }}
                            </span>
                        </td>
                    @endif

                    {{-- Producto --}}
                    <td class="px-4 py-3 font-semibold text-gray-900 capitalize">
                        {{ $productoNombre }}
                    </td>

                    {{-- Marca --}}
                    <td class="px-4 py-3 text-gray-600 capitalize">
                        {{ $marcaNombre }}
                    </td>

                    {{-- Talla --}}
                    <td class="px-4 py-3 text-center">
                        <span class="inline-block px-2.5 py-1 text-xs font-bold font-mono bg-gray-100 text-gray-800 rounded border border-gray-200">
                            {{ $talla }}
                        </span>
                    </td>

                    {{-- Stock con Alerta Visual --}}
                    <td class="px-4 py-3 text-center">
                        @if ($stock <= 3)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold bg-red-50 text-red-700 rounded-full border border-red-200" title="Bajo stock">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-600 animate-pulse"></span>
                                {{ $stock }} unidades
                            </span>
                        @elseif ($stock <= 6)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold bg-amber-50 text-amber-700 rounded-full border border-amber-200" title="Stock moderado">
                                {{ $stock }} unidades
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-700 rounded-full">
                                {{ $stock }} unidades
                            </span>
                        @endif
                    </td>

                    {{-- Estatus --}}
                    <td class="px-4 py-3">
                        @if ($estatus === 'Activo')
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Activo</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">Inactivo</span>
                        @endif
                    </td>

                    {{-- Acciones --}}
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            
                            {{-- Ícono VER (Detalle en Modal) --}}
                            <button type="button" 
                                    data-item='@json($datosModal)'
                                    onclick="abrirModalInventario(this)" 
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition-colors" 
                                    title="Ver detalle">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            @if ($puedeGestionar ?? false)
                                {{-- Ícono EDITAR --}}
                                <a href="{{ url('/inventario/editar/' . data_get($inventario, 'id', '')) }}" 
                                   class="p-1.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors" 
                                   title="Editar registro">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>

                                {{-- Ícono ELIMINAR --}}
                                <a href="{{ url('/inventario/mostrar/' . data_get($inventario, 'id', '')) }}" 
                                   class="p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors" 
                                   title="Eliminar registro">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </a>
                            @endif

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ ($puedeGestionar ?? false) ? 8 : 7 }}" class="px-4 py-6 text-center text-gray-500">
                        No hay registros de inventario.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal de Detalle de Inventario (Adaptado sin foto) --}}
<div id="modalInventario" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transition-all">
        
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 id="modal-producto" class="text-lg font-bold text-gray-900">Detalle de Inventario</h3>
                <span id="modal-id" class="text-xs text-gray-400"></span>
            </div>
            <button onclick="cerrarModalInventario()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-900">
                ✕
            </button>
        </div>

        {{-- Información Detallada --}}
        <div class="mt-4 space-y-3 text-sm">
            
            {{-- Ubicación y Marca --}}
            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 space-y-2">
                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Ubicación y Calzado</span>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="block text-xs text-gray-500">Sucursal</span>
                        <strong id="modal-sucursal" class="text-gray-900"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Marca</span>
                        <strong id="modal-marca" class="text-gray-900"></strong>
                    </div>
                </div>
            </div>

            {{-- Talla, Stock y Estatus --}}
            <div class="bg-blue-50/60 p-3.5 rounded-xl border border-blue-100 space-y-2">
                <span class="block text-xs font-semibold text-blue-700 uppercase tracking-wider">Existencias & Talla</span>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <span class="block text-xs text-gray-500">Talla</span>
                        <strong id="modal-talla" class="text-gray-900 font-mono text-base"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Disponible</span>
                        <strong id="modal-stock" class="text-gray-900 text-base"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Estatus</span>
                        <span id="modal-estatus" class="block pt-1"></span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="mt-6 flex justify-end">
            <button onclick="cerrarModalInventario()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    function abrirModalInventario(btn) {
        const item = JSON.parse(btn.getAttribute('data-item'));

        document.getElementById('modal-id').innerText = 'ID Registro: #' + (item.id || 'N/A');
        document.getElementById('modal-producto').innerText = item.producto || 'Producto';
        document.getElementById('modal-sucursal').innerText = item.sucursal || '—';
        document.getElementById('modal-marca').innerText = item.marca || '—';
        document.getElementById('modal-talla').innerText = item.talla || '—';
        document.getElementById('modal-stock').innerText = item.stock + ' pzs';

        const contenedorEstatus = document.getElementById('modal-estatus');
        if (item.estatus === 'Activo') {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">Activo</span>';
        } else {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20">Inactivo</span>';
        }

        document.getElementById('modalInventario').classList.remove('hidden');
    }

    function cerrarModalInventario() {
        document.getElementById('modalInventario').classList.add('hidden');
    }
</script>

@endsection