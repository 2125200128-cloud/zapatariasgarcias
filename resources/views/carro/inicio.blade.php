@extends('/plantilla/base')

@section('dinamico')

{{-- Título de página / Imagen y Nombre --}}
<div class="flex flex-wrap items-center justify-between gap-2 mb-10 mt-4 px-4">
    <div class="flex items-center gap-4" class="shrink-0 transition-transform hover:scale-105">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/carros-inicio.png') }}" alt="carros-inicio" class="w-18 h-18 object-contain">
        </a>
        <div>
            <h1 class="text-4xl font-serif text-brand-dark px-4">Vehívulos</h1>
            <p class="font-serif text-[#715b49] px-4">Gestion y cuida de tus vehículos</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($carros ?? $vehiculos ?? []) }} registrados</span>
        <a href="{{ url('/carro/formulario') }}" class="bg-brand-black-coffe text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-brand-caramel transition-colors shadow-sm">
            Nuevo vehículo
        </a>
    </div>
</div>
{{-- Título de página / Imagen y Nombre - FIN --}}

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
                <th class="px-4 py-3">Vehículo</th>
                <th class="px-4 py-3">Placas</th>
                <th class="px-4 py-3">Conductor Asignado</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($carros ?? $vehiculos ?? [] as $carro)
                @php
                    // Normalizar foto / imagen
                    $fotoRaw = data_get($carro, 'imagen') ?? data_get($carro, 'foto');
                    $fotoUrl = $fotoRaw ? (str_starts_with($fotoRaw, 'http') ? $fotoRaw : asset($fotoRaw)) : asset('images/sin-imagen.jpg');

                    // Armar nombre del vehículo (Marca + Modelo o Nombre)
                    $nombreVehiculo = trim(data_get($carro, 'marca', '') . ' ' . data_get($carro, 'modelo', ''));
                    if (empty($nombreVehiculo)) {
                        $nombreVehiculo = data_get($carro, 'nombre', 'Vehículo');
                    }

                    // Conductor / Chofer asignado
                    $chofer = data_get($carro, 'chofer.nombre') 
                            ?? data_get($carro, 'conductor.nombre') 
                            ?? data_get($carro, 'empleado.nombre') 
                            ?? data_get($carro, 'chofer') 
                            ?? data_get($carro, 'conductor', 'Sin asignar');

                    // Paquete de datos para el Modal
                    $datosModal = [
                        'id'           => data_get($carro, 'id'),
                        'nombre'       => $nombreVehiculo,
                        'marca'        => data_get($carro, 'marca', '—'),
                        'modelo'       => data_get($carro, 'modelo', '—'),
                        'anio'         => data_get($carro, 'anio') ?? data_get($carro, 'año', '—'),
                        'color'        => data_get($carro, 'color', '—'),
                        'placas'       => data_get($carro, 'placas') ?? data_get($carro, 'placa', '—'),
                        'serie'        => data_get($carro, 'numero_serie') ?? data_get($carro, 'vin', '—'),
                        'capacidad'    => data_get($carro, 'capacidad', '—'),
                        'chofer'       => $chofer,
                        'estatus'      => data_get($carro, 'estatus', 'Activo'),
                        'foto'         => $fotoUrl,
                    ];
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors">
                    {{-- ID Discreto --}}
                    <td class="px-4 py-3 font-semibold text-gray-400">#{{ data_get($carro, 'id') }}</td>

                    {{-- Foto + Marca/Modelo + Año/Color --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $fotoUrl }}" alt="Foto del vehículo" class="h-10 w-12 rounded-lg object-cover border border-gray-200 flex-shrink-0 shadow-sm bg-gray-50">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $nombreVehiculo }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ data_get($carro, 'color', 'Sin color') }} 
                                    @if(data_get($carro, 'anio') || data_get($carro, 'año'))
                                        • {{ data_get($carro, 'anio') ?? data_get($carro, 'año') }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </td>

                    {{-- Placas estilo matrícula --}}
                    <td class="px-4 py-3">
                        <span class="inline-block px-2.5 py-1 font-mono text-xs font-bold bg-gray-100 text-gray-800 rounded border border-gray-200 uppercase tracking-wider">
                            {{ data_get($carro, 'placas') ?? data_get($carro, 'placa', '—') }}
                        </span>
                    </td>

                    {{-- Conductor asignado --}}
                    <td class="px-4 py-3 text-gray-700 font-medium">
                        {{ $chofer }}
                    </td>

                    {{-- Estatus --}}
                    <td class="px-4 py-3">
                        @php $estatus = data_get($carro, 'estatus', 'Activo'); @endphp
                        @if ($estatus === 'Activo' || $estatus === 'Disponible')
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">Activo</span>
                        @elseif ($estatus === 'En Servicio' || $estatus === 'Mantenimiento')
                            <span class="inline-flex items-center rounded-full bg-yellow-50 px-2.5 py-1 text-xs font-medium text-yellow-700 ring-1 ring-inset ring-yellow-600/20">En Servicio</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">Inactivo</span>
                        @endif
                    </td>

                    {{-- Botones de Acción con ÍCONOS SVG --}}
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-2">
                            
                            {{-- Ícono VER (Ojo) --}}
                            <button type="button" 
                                    data-carro='@json($datosModal)'
                                    onclick="abrirModalCarro(this)" 
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition-colors" 
                                    title="Ver expediente del vehículo">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            {{-- Ícono EDITAR (Lápiz) --}}
                            <a href="{{ url('/carro/editar/' . data_get($carro, 'id')) }}" 
                               class="p-1.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors" 
                               title="Editar vehículo">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- Ícono ELIMINAR (Basura) --}}
                            <a href="{{ url('/carro/mostrar/' . data_get($carro, 'id')) }}" 
                               class="p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors" 
                               title="Eliminar vehículo">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </a>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay vehículos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal de Ficha Técnica / Expediente del Vehículo --}}
<div id="modalCarro" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl transition-all">
        
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 id="modal-nombre" class="text-lg font-bold text-gray-900">Detalles del Vehículo</h3>
                <span id="modal-id" class="text-xs text-gray-400"></span>
            </div>
            <button onclick="cerrarModalCarro()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-900">
                ✕
            </button>
        </div>

        {{-- Foto Principal --}}
        <div class="my-4 flex items-center justify-center">
            <div class="h-40 w-full rounded-xl border border-gray-200 overflow-hidden bg-gray-50 shadow-inner">
                <img id="modal-foto" src="" class="w-full h-full object-cover">
            </div>
        </div>

        {{-- Datos en Grid --}}
        <div class="space-y-3 text-sm">
            
            {{-- Especificaciones principales --}}
            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 space-y-2">
                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Especificaciones</span>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <span class="block text-xs text-gray-500">Placas</span>
                        <strong id="modal-placas" class="text-gray-900 font-mono"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Año</span>
                        <strong id="modal-anio" class="text-gray-900"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Color</span>
                        <strong id="modal-color" class="text-gray-900"></strong>
                    </div>
                </div>
            </div>

            {{-- Asignación y Capacidad --}}
            <div class="bg-blue-50/60 p-3.5 rounded-xl border border-blue-100 space-y-2">
                <span class="block text-xs font-semibold text-blue-700 uppercase tracking-wider">Asignación & Operación</span>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="block text-xs text-gray-500">Conductor Asignado</span>
                        <strong id="modal-chofer" class="text-gray-900"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Capacidad</span>
                        <strong id="modal-capacidad" class="text-gray-900"></strong>
                    </div>
                </div>
            </div>

            {{-- Identificación y Estatus --}}
            <div class="grid grid-cols-2 gap-3 bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                <div>
                    <span class="block text-xs text-gray-500">Número de Serie (VIN)</span>
                    <strong id="modal-serie" class="text-gray-900 font-mono text-xs break-all"></strong>
                </div>
                <div>
                    <span class="block text-xs text-gray-500">Estatus actual</span>
                    <span id="modal-estatus"></span>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="mt-6 flex justify-end">
            <button onclick="cerrarModalCarro()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    function abrirModalCarro(btn) {
        // Lee los datos JSON de forma segura
        const c = JSON.parse(btn.getAttribute('data-carro'));

        // Cargar datos principales
        document.getElementById('modal-id').innerText = 'ID Registro: #' + (c.id || 'N/A');
        document.getElementById('modal-nombre').innerText = c.nombre || 'Vehículo';
        document.getElementById('modal-placas').innerText = c.placas || '—';
        document.getElementById('modal-anio').innerText = c.anio || '—';
        document.getElementById('modal-color').innerText = c.color || '—';
        document.getElementById('modal-chofer').innerText = c.chofer || 'Sin asignar';
        document.getElementById('modal-capacidad').innerText = c.capacidad || '—';
        document.getElementById('modal-serie').innerText = c.serie || '—';
        document.getElementById('modal-foto').src = c.foto;

        // Badge de Estatus
        const contenedorEstatus = document.getElementById('modal-estatus');
        if (c.estatus === 'Activo' || c.estatus === 'Disponible') {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">Activo</span>';
        } else if (c.estatus === 'En Servicio' || c.estatus === 'Mantenimiento') {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-yellow-50 px-2.5 py-0.5 text-xs font-semibold text-yellow-700 ring-1 ring-inset ring-yellow-600/20">En Servicio</span>';
        } else {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20">Inactivo</span>';
        }

        // Mostrar Modal
        document.getElementById('modalCarro').classList.remove('hidden');
    }

    function cerrarModalCarro() {
        document.getElementById('modalCarro').classList.add('hidden');
    }
</script>

@endsection