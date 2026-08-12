@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Proveedores</h1>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($proveedores ?? []) }} registros</span>
        <a href="{{ url('/proveedor/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
            Nuevo proveedor
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

{{-- Tabla Principal Limpia --}}
<div class="bg-white rounded-lg shadow-sm overflow-x-auto border border-gray-100">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Proveedor</th>
                <th class="px-4 py-3">Contacto / Correo</th>
                <th class="px-4 py-3">Municipio</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($proveedores ?? [] as $proveedor)
                @php
                    // Preparamos los datos completos para el modal
                    $datosModal = [
                        'id'          => data_get($proveedor, 'id'),
                        'nombre'      => data_get($proveedor, 'nombre', '—'),
                        'contacto'    => data_get($proveedor, 'contacto', '—'),
                        'correo'      => data_get($proveedor, 'correo', '—'),
                        'municipio'   => data_get($proveedor, 'municipio', '—'),
                        'estado'      => data_get($proveedor, 'estado', '—'),
                        'direccion'   => data_get($proveedor, 'direccion', '—'),
                        'rfc'         => data_get($proveedor, 'rfc', '—'),
                        'estatus'     => data_get($proveedor, 'estatus', 'Activo'),
                    ];
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-4 py-3 font-semibold text-gray-500">#{{ data_get($proveedor, 'id') }}</td>

                    {{-- Nombre del proveedor --}}
                    <td class="px-4 py-3 font-medium text-gray-900">
                        {{ data_get($proveedor, 'nombre', '—') }}
                    </td>

                    {{-- Teléfono y Correo juntos --}}
                    <td class="px-4 py-3">
                        <div class="flex flex-col">
                            <span class="text-gray-900 font-medium">{{ data_get($proveedor, 'contacto', '—') }}</span>
                            <span class="text-xs text-gray-500">{{ data_get($proveedor, 'correo', '—') }}</span>
                        </div>
                    </td>

                    <td class="px-4 py-3 text-gray-700">{{ data_get($proveedor, 'municipio', '—') }}</td>

                    {{-- Estatus --}}
                    <td class="px-4 py-3">
                        @if (data_get($proveedor, 'estatus') === 'Activo')
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Activo</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">Inactivo</span>
                        @endif
                    </td>

                    {{-- Botones de Acción con ÍCONOS SVG --}}
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            
                            {{-- Ícono VER (Ojo) --}}
                            <button type="button" 
                                    data-proveedor='@json($datosModal)'
                                    onclick="abrirModalProveedor(this)" 
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition-colors" 
                                    title="Ver detalle completo">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            {{-- Ícono EDITAR (Lápiz) --}}
                            <a href="{{ url('/proveedor/editar/' . data_get($proveedor, 'id')) }}" 
                               class="p-1.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors" 
                               title="Editar proveedor">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- Ícono ELIMINAR (Basura) --}}
                            <a href="{{ url('/proveedor/mostrar/' . data_get($proveedor, 'id')) }}" 
                               class="p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors" 
                               title="Eliminar proveedor">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </a>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay proveedores registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal de Detalle del Proveedor --}}
<div id="modalProveedor" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl transition-all">
        
        {{-- Header del Modal --}}
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 id="modal-nombre" class="text-lg font-bold text-gray-900">Detalles del Proveedor</h3>
                <span id="modal-id" class="text-xs text-gray-400"></span>
            </div>
            <button onclick="cerrarModalProveedor()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-900">
                ✕
            </button>
        </div>

        {{-- Cuerpo de datos en Grid --}}
        <div class="my-4 space-y-4">
            
            {{-- Sección Contacto Principal --}}
            <div class="bg-blue-50/60 p-3.5 rounded-xl border border-blue-100">
                <span class="block text-xs font-semibold text-blue-700 uppercase tracking-wider mb-2">Información de Contacto</span>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="block text-xs text-gray-500">Teléfono / Contacto</span>
                        <strong id="modal-contacto" class="text-gray-900"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Correo Electrónico</span>
                        <strong id="modal-correo" class="text-gray-900 break-all"></strong>
                    </div>
                </div>
            </div>

            {{-- Sección Ubicación --}}
            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Ubicación y Dirección</span>
                <div class="grid grid-cols-2 gap-3 text-sm mb-2">
                    <div>
                        <span class="block text-xs text-gray-500">Municipio</span>
                        <strong id="modal-municipio" class="text-gray-900"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Estado</span>
                        <strong id="modal-estado" class="text-gray-900"></strong>
                    </div>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Dirección completa</span>
                    <strong id="modal-direccion" class="text-gray-900 font-normal"></strong>
                </div>
            </div>

            {{-- Datos Fiscales / Registro --}}
            <div class="grid grid-cols-2 gap-3 text-sm bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                <div>
                    <span class="block text-xs text-gray-500">RFC</span>
                    <strong id="modal-rfc" class="text-gray-900"></strong>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Estatus actual</span>
                    <span id="modal-estatus"></span>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="mt-5 flex justify-end">
            <button onclick="cerrarModalProveedor()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    function abrirModalProveedor(btn) {
        // Lee los datos JSON de forma segura
        const p = JSON.parse(btn.getAttribute('data-proveedor'));

        // Rellenar datos en el modal
        document.getElementById('modal-nombre').innerText = p.nombre || 'Proveedor';
        document.getElementById('modal-id').innerText = 'ID Registro: #' + (p.id || 'N/A');
        document.getElementById('modal-contacto').innerText = p.contacto || '—';
        document.getElementById('modal-correo').innerText = p.correo || '—';
        document.getElementById('modal-municipio').innerText = p.municipio || '—';
        document.getElementById('modal-estado').innerText = p.estado || '—';
        document.getElementById('modal-direccion').innerText = p.direccion || 'No especificada';
        document.getElementById('modal-rfc').innerText = p.rfc || '—';

        // Badge para el estatus
        const contenedorEstatus = document.getElementById('modal-estatus');
        if (p.estatus === 'Activo') {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-green-50 px-2 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">Activo</span>';
        } else {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-red-50 px-2 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20">Inactivo</span>';
        }

        // Mostrar Modal
        document.getElementById('modalProveedor').classList.remove('hidden');
    }

    function cerrarModalProveedor() {
        document.getElementById('modalProveedor').classList.add('hidden');
    }
</script>

@endsection