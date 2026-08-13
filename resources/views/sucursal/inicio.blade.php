@extends('/plantilla/base')

@section('dinamico')

{{-- Título de página / Imagen y Nombre --}}
<div class="flex flex-wrap items-center justify-between gap-2 mb-10 mt-4 px-4">
    <div class="flex items-center gap-4" class="shrink-0 transition-transform hover:scale-105">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/sucursal-formulario.png') }}" alt="inicio-sucursales" class="w-18 h-18 object-contain">
        </a>
        <div>
            <h1 class="text-4xl font-serif text-brand-dark px-4">Sucursales</h1>
            <p class="font-serif text-[#715b49] px-4">Gestiona tus sucursales en tiempo real</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($sucursales ?? []) }} registros</span>
        <a href="{{ url('/sucursal/formulario') }}" class="bg-brand-black-coffe text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-brand-caramel transition-colors shadow-sm">
            Nueva sucursal
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
                <th class="px-4 py-3">Sucursal</th>
                <th class="px-4 py-3">Municipio</th>
                <th class="px-4 py-3">Contacto</th>
                <th class="px-4 py-3">Encargado</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($sucursales ?? [] as $sucursal)
                @php
                    // Normalizar foto / imagen
                    $fotoRaw = data_get($sucursal, 'imagen') ?? data_get($sucursal, 'foto');
                    $fotoUrl = $fotoRaw ? (str_starts_with($fotoRaw, 'http') ? $fotoRaw : asset($fotoRaw)) : asset('images/sucursal-placeholder.jpg');

                    // Encargado asignado — la API lo manda bajo la relación
                    // 'empleado', no 'encargado' (por eso siempre salía "Sin
                    // asignar" aunque la sucursal sí tuviera encargado).
                    $encargado = trim(data_get($sucursal, 'empleado.nombre', '') . ' ' . data_get($sucursal, 'empleado.apellido_paterno', ''));
                    $encargado = $encargado !== '' ? $encargado : 'Sin asignar';

                    // Paquete de datos para el Modal
                    $datosModal = [
                        'id'        => data_get($sucursal, 'id'),
                        'nombre'    => data_get($sucursal, 'nombre', 'Sucursal'),
                        'municipio' => data_get($sucursal, 'municipio', '—'),
                        'direccion' => data_get($sucursal, 'direccion', '—'),
                        'contacto'  => data_get($sucursal, 'contacto') ?? data_get($sucursal, 'telefono', '—'),
                        'encargado' => $encargado,
                        'estatus'   => data_get($sucursal, 'estatus', 'Activo'),
                        'foto'      => $fotoUrl,
                    ];
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors">
                    {{-- ID Discreto --}}
                    <td class="px-4 py-3 font-semibold text-gray-400">#{{ data_get($sucursal, 'id') }}</td>

                    {{-- Imagen + Nombre de Sucursal --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $fotoUrl }}" alt="Foto sucursal" class="h-10 w-12 rounded-lg object-cover border border-gray-200 flex-shrink-0 shadow-sm bg-gray-50">
                            <div>
                                <p class="font-semibold text-gray-900">{{ data_get($sucursal, 'nombre', '—') }}</p>
                                @if(data_get($sucursal, 'direccion'))
                                    <p class="text-xs text-gray-500 truncate max-w-xs">{{ data_get($sucursal, 'direccion') }}</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Municipio --}}
                    <td class="px-4 py-3 font-medium text-gray-700">
                        {{ data_get($sucursal, 'municipio', '—') }}
                    </td>

                    {{-- Teléfono / Contacto --}}
                    <td class="px-4 py-3 font-medium text-gray-700">
                        {{ data_get($sucursal, 'contacto') ?? data_get($sucursal, 'telefono', '—') }}
                    </td>

                    {{-- Encargado --}}
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-1.5 font-medium text-gray-800">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                            {{ $encargado }}
                        </span>
                    </td>

                    {{-- Estatus --}}
                    <td class="px-4 py-3">
                        @if (data_get($sucursal, 'estatus', 'Activo') === 'Activo')
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
                                    data-sucursal='@json($datosModal)'
                                    onclick="abrirModalSucursal(this)" 
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition-colors" 
                                    title="Ver ficha técnica de la sucursal">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            {{-- Ícono EDITAR (Lápiz) --}}
                            <a href="{{ url('/sucursal/editar/' . data_get($sucursal, 'id')) }}" 
                               class="p-1.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors" 
                               title="Editar sucursal">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- Ícono ELIMINAR (Basura) --}}
                            <a href="{{ url('/sucursal/mostrar/' . data_get($sucursal, 'id')) }}" 
                               class="p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors" 
                               title="Eliminar sucursal">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </a>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">No hay sucursales registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal de Ficha Técnica de la Sucursal --}}
<div id="modalSucursal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl transition-all">
        
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 id="modal-nombre" class="text-lg font-bold text-gray-900">Detalles de la Sucursal</h3>
                <span id="modal-id" class="text-xs text-gray-400"></span>
            </div>
            <button onclick="cerrarModalSucursal()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-900">
                ✕
            </button>
        </div>

        {{-- Fotografía de la Fachada --}}
        <div class="my-4 flex items-center justify-center">
            <div class="h-44 w-full rounded-xl border border-gray-200 overflow-hidden bg-gray-50 shadow-inner">
                <img id="modal-foto" src="" class="w-full h-full object-cover">
            </div>
        </div>

        {{-- Información Detallada --}}
        <div class="space-y-3 text-sm">
            
            {{-- Ubicación y Municipio --}}
            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 space-y-2">
                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Ubicación</span>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="block text-xs text-gray-500">Municipio</span>
                        <strong id="modal-municipio" class="text-gray-900"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Estatus</span>
                        <span id="modal-estatus"></span>
                    </div>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Dirección</span>
                    <strong id="modal-direccion" class="text-gray-900 text-xs"></strong>
                </div>
            </div>

            {{-- Encargado y Contacto --}}
            <div class="bg-blue-50/60 p-3.5 rounded-xl border border-blue-100 space-y-2">
                <span class="block text-xs font-semibold text-blue-700 uppercase tracking-wider">Administración & Contacto</span>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="block text-xs text-gray-500">Encargado General</span>
                        <strong id="modal-encargado" class="text-gray-900"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Teléfono / Contacto</span>
                        <strong id="modal-contacto" class="text-gray-900"></strong>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="mt-6 flex justify-end">
            <button onclick="cerrarModalSucursal()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    function abrirModalSucursal(btn) {
        // Lee los datos JSON de forma segura
        const s = JSON.parse(btn.getAttribute('data-sucursal'));

        // Cargar datos principales
        document.getElementById('modal-id').innerText = 'ID Registro: #' + (s.id || 'N/A');
        document.getElementById('modal-nombre').innerText = s.nombre || 'Sucursal';
        document.getElementById('modal-municipio').innerText = s.municipio || '—';
        document.getElementById('modal-direccion').innerText = s.direccion || 'No especificada';
        document.getElementById('modal-encargado').innerText = s.encargado || 'Sin asignar';
        document.getElementById('modal-contacto').innerText = s.contacto || '—';
        document.getElementById('modal-foto').src = s.foto;

        // Badge de Estatus
        const contenedorEstatus = document.getElementById('modal-estatus');
        if (s.estatus === 'Activo') {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">Activo</span>';
        } else {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20">Inactivo</span>';
        }

        // Mostrar Modal
        document.getElementById('modalSucursal').classList.remove('hidden');
    }

    function cerrarModalSucursal() {
        document.getElementById('modalSucursal').classList.add('hidden');
    }
</script>

@endsection