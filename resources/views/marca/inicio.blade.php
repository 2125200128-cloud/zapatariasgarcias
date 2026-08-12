@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Marcas</h1>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($marcas ?? []) }} marcas registradas</span>
        <a href="{{ url('/marca/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
            Nueva marca
        </a>
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
                <th class="px-4 py-3">Marca</th>
                <th class="px-4 py-3">Proveedor Asociado</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($marcas ?? [] as $marca)
                @php
                    // Normalizar imagen/logo (Local o Cloudinary)
                    $imagenRaw = data_get($marca, 'imagen') ?? data_get($marca, 'logo');
                    $imagenUrl = $imagenRaw ? (str_starts_with($imagenRaw, 'http') ? $imagenRaw : asset($imagenRaw)) : asset('images/sin-imagen.jpg');

                    // Relación de proveedor
                    $nombreProveedor = data_get($marca, 'proveedor.nombre') ?? data_get($marca, 'proveedor', '—');

                    // Paquete de datos para el modal
                    $datosModal = [
                        'id'        => data_get($marca, 'id'),
                        'nombre'    => data_get($marca, 'nombre', '—'),
                        'imagen'    => $imagenUrl,
                        'proveedor' => $nombreProveedor,
                        'estatus'   => data_get($marca, 'estatus', 'Activo'),
                    ];
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors">
                    {{-- ID Discreto --}}
                    <td class="px-4 py-3 font-semibold text-gray-400">#{{ data_get($marca, 'id') }}</td>

                    {{-- Logo + Nombre de la Marca --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-12 rounded-lg border border-gray-200 bg-white p-1 flex items-center justify-center flex-shrink-0 shadow-sm">
                                <img src="{{ $imagenUrl }}" alt="{{ data_get($marca, 'nombre') }}" class="max-h-full max-w-full object-contain">
                            </div>
                            <span class="font-semibold text-gray-900 capitalize">{{ data_get($marca, 'nombre', '—') }}</span>
                        </div>
                    </td>

                    {{-- Proveedor --}}
                    <td class="px-4 py-3 text-gray-700 font-medium">
                        {{ $nombreProveedor }}
                    </td>

                    {{-- Estatus --}}
                    <td class="px-4 py-3">
                        @if (data_get($marca, 'estatus', 'Activo') === 'Activo')
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Activo</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">Inactivo</span>
                        @endif
                    </td>

                    {{-- Botones de Acción con Íconos SVG --}}
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            
                            {{-- Ícono VER (Ojo) --}}
                            <button type="button" 
                                    data-marca='@json($datosModal)'
                                    onclick="abrirModalMarca(this)" 
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition-colors" 
                                    title="Ver detalle de la marca">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            {{-- Ícono EDITAR (Lápiz) --}}
                            <a href="{{ url('/marca/editar/' . data_get($marca, 'id')) }}" 
                               class="p-1.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors" 
                               title="Editar marca">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- Ícono ELIMINAR (Basura) --}}
                            <a href="{{ url('/marca/mostrar/' . data_get($marca, 'id')) }}" 
                               class="p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors" 
                               title="Eliminar marca">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </a>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">No hay marcas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal de Detalle de la Marca --}}
<div id="modalMarca" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transition-all">
        
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 id="modal-nombre" class="text-xl font-bold text-gray-900 capitalize">Detalles de Marca</h3>
                <span id="modal-id" class="text-xs text-gray-400"></span>
            </div>
            <button onclick="cerrarModalMarca()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-900">
                ✕
            </button>
        </div>

        {{-- Logo Principal --}}
        <div class="my-5 flex flex-col items-center justify-center">
            <div class="h-28 w-44 rounded-xl border border-gray-200 bg-white p-3 flex items-center justify-center shadow-sm">
                <img id="modal-img" src="" class="max-h-full max-w-full object-contain">
            </div>
        </div>

        {{-- Datos en Grid --}}
        <div class="bg-gray-50 p-4 rounded-xl space-y-3 text-sm border border-gray-100">
            <div class="flex justify-between items-center border-b border-gray-200/60 pb-2">
                <span class="text-xs text-gray-500">Proveedor Asociado</span>
                <strong id="modal-proveedor" class="text-gray-900"></strong>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-xs text-gray-500">Estatus actual</span>
                <span id="modal-estatus"></span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-6 flex justify-end">
            <button onclick="cerrarModalMarca()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    function abrirModalMarca(btn) {
        // Lee los datos JSON embebidos
        const m = JSON.parse(btn.getAttribute('data-marca'));

        // Cargar datos en el modal
        document.getElementById('modal-nombre').innerText = m.nombre || 'Marca';
        document.getElementById('modal-id').innerText = 'ID Registro: #' + (m.id || 'N/A');
        document.getElementById('modal-proveedor').innerText = m.proveedor || 'No asignado';
        document.getElementById('modal-img').src = m.imagen;

        // Badge de estatus
        const contenedorEstatus = document.getElementById('modal-estatus');
        if (m.estatus === 'Activo') {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">Activo</span>';
        } else {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20">Inactivo</span>';
        }

        // Mostrar Modal
        document.getElementById('modalMarca').classList.remove('hidden');
    }

    function cerrarModalMarca() {
        document.getElementById('modalMarca').classList.add('hidden');
    }
</script>

@endsection