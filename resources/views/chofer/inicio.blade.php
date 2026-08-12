@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Choferes</h1>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($choferes ?? []) }} choferes registrados</span>
        <a href="{{ url('/chofer/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
            Nuevo chofer
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
                <th class="px-4 py-3">Chofer</th>
                <th class="px-4 py-3">Contacto</th>
                <th class="px-4 py-3">Trayecto Activo</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($choferes ?? [] as $chofer)
                @php
                    // Normalizar foto / avatar
                    $fotoRaw = data_get($chofer, 'imagen') ?? data_get($chofer, 'foto');
                    $fotoUrl = $fotoRaw ? (str_starts_with($fotoRaw, 'http') ? $fotoRaw : asset($fotoRaw)) : asset('images/avatar-placeholder.jpg');

                    // Armar Nombre Completo (Nombre + Apellido)
                    $nombreCompleto = trim(
                        data_get($chofer, 'nombre', '') . ' ' . 
                        data_get($chofer, 'apellido', '') . ' ' . 
                        data_get($chofer, 'apellidos', '')
                    );

                    // Trayecto Activo
                    $trayecto = data_get($chofer, 'trayecto_activo') ?? data_get($chofer, 'trayecto', 'Libre');

                    // Paquete de datos para el Modal
                    $datosModal = [
                        'id'              => data_get($chofer, 'id'),
                        'nombre_completo' => $nombreCompleto ?: 'Sin nombre',
                        'contacto'        => data_get($chofer, 'contacto') ?? data_get($chofer, 'telefono', '—'),
                        'correo'          => data_get($chofer, 'correo', '—'),
                        'licencia'        => data_get($chofer, 'licencia', '—'),
                        'trayecto'        => $trayecto,
                        'estatus'         => data_get($chofer, 'estatus', 'Activo'),
                        'foto'            => $fotoUrl,
                    ];
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors">
                    {{-- ID Discreto --}}
                    <td class="px-4 py-3 font-semibold text-gray-400">#{{ data_get($chofer, 'id') }}</td>

                    {{-- Avatar + Nombre Completo --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $fotoUrl }}" alt="Foto chofer" class="h-10 w-10 rounded-full object-cover border border-gray-200 flex-shrink-0 shadow-sm bg-gray-100">
                            <div>
                                <p class="font-semibold text-gray-900 capitalize">{{ $nombreCompleto }}</p>
                                @if(data_get($chofer, 'licencia'))
                                    <p class="text-xs text-gray-500">Lic: {{ data_get($chofer, 'licencia') }}</p>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Contacto --}}
                    <td class="px-4 py-3 font-medium text-gray-700">
                        {{ data_get($chofer, 'contacto') ?? data_get($chofer, 'telefono', '—') }}
                    </td>

                    {{-- Trayecto Activo (Insignia visual) --}}
                    <td class="px-4 py-3">
                        @if ($trayecto === 'Libre' || empty($trayecto) || $trayecto === '—')
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-600">
                                Libre
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-800 ring-1 ring-inset ring-amber-600/20">
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                {{ $trayecto }}
                            </span>
                        @endif
                    </td>

                    {{-- Estatus --}}
                    <td class="px-4 py-3">
                        @if (data_get($chofer, 'estatus', 'Activo') === 'Activo')
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
                                    data-chofer='@json($datosModal)'
                                    onclick="abrirModalChofer(this)" 
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition-colors" 
                                    title="Ver expediente del chofer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            {{-- Ícono EDITAR (Lápiz) --}}
                            <a href="{{ url('/chofer/editar/' . data_get($chofer, 'id')) }}" 
                               class="p-1.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors" 
                               title="Editar chofer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- Ícono ELIMINAR (Basura) --}}
                            <a href="{{ url('/chofer/mostrar/' . data_get($chofer, 'id')) }}" 
                               class="p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors" 
                               title="Eliminar chofer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </a>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay choferes registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal de Expediente del Chofer --}}
<div id="modalChofer" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl transition-all">
        
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Expediente del Chofer</h3>
                <span id="modal-id" class="text-xs text-gray-400"></span>
            </div>
            <button onclick="cerrarModalChofer()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-900">
                ✕
            </button>
        </div>

        {{-- Perfil del Chofer --}}
        <div class="my-4 flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
            <img id="modal-foto" src="" class="h-16 w-16 rounded-full object-cover border-2 border-white shadow-md bg-white flex-shrink-0">
            <div class="space-y-1">
                <h4 id="modal-nombre-completo" class="font-bold text-gray-900 text-base leading-tight capitalize"></h4>
                <div class="pt-1 flex items-center gap-2">
                    <span id="modal-estatus-badge"></span>
                </div>
            </div>
        </div>

        {{-- Detalles de Operación y Contacto --}}
        <div class="space-y-3 text-sm">
            
            {{-- Estado de Ruta Activa --}}
            <div class="bg-amber-50/70 p-3.5 rounded-xl border border-amber-100 space-y-1">
                <span class="block text-xs font-semibold text-amber-800 uppercase tracking-wider">Estado de Asignación</span>
                <p id="modal-trayecto" class="font-medium text-amber-900 text-sm"></p>
            </div>

            {{-- Datos Personales y de Contacto --}}
            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 space-y-2">
                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Información de Contacto</span>
                <div class="grid grid-cols-2 gap-3">
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

            {{-- Licencia --}}
            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                <span class="block text-xs text-gray-500">Licencia de Conducir</span>
                <strong id="modal-licencia" class="text-gray-900 font-mono"></strong>
            </div>

        </div>

        {{-- Footer --}}
        <div class="mt-6 flex justify-end">
            <button onclick="cerrarModalChofer()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    function abrirModalChofer(btn) {
        // Lee los datos JSON de forma segura
        const c = JSON.parse(btn.getAttribute('data-chofer'));

        // Cargar datos en el modal
        document.getElementById('modal-id').innerText = 'ID Registro: #' + (c.id || 'N/A');
        document.getElementById('modal-nombre-completo').innerText = c.nombre_completo || 'Chofer';
        document.getElementById('modal-contacto').innerText = c.contacto || '—';
        document.getElementById('modal-correo').innerText = c.correo || '—';
        document.getElementById('modal-licencia').innerText = c.licencia || 'No registrada';
        document.getElementById('modal-trayecto').innerText = c.trayecto || 'Actualmente libre';
        document.getElementById('modal-foto').src = c.foto;

        // Badge de Estatus
        const estatusContenedor = document.getElementById('modal-estatus-badge');
        if (c.estatus === 'Activo') {
            estatusContenedor.innerHTML = '<span class="px-2 py-0.5 text-xs font-semibold bg-green-100 text-green-700 rounded-md">Activo</span>';
        } else {
            estatusContenedor.innerHTML = '<span class="px-2 py-0.5 text-xs font-semibold bg-red-100 text-red-700 rounded-md">Inactivo</span>';
        }

        // Mostrar Modal
        document.getElementById('modalChofer').classList.remove('hidden');
    }

    function cerrarModalChofer() {
        document.getElementById('modalChofer').classList.add('hidden');
    }
</script>

@endsection