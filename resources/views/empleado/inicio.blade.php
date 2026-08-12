@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Empleados</h1>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($empleados ?? []) }} empleados registrados</span>
        <a href="{{ url('/empleado/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors shadow-sm">
            Nuevo empleado
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
                <th class="px-4 py-3">Empleado</th>
                <th class="px-4 py-3">Contacto / Correo</th>
                <th class="px-4 py-3">Rol</th>
                <th class="px-4 py-3">Municipio</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($empleados ?? [] as $empleado)
                @php
                    // Normalizar foto / avatar
                    $fotoRaw = data_get($empleado, 'imagen') ?? data_get($empleado, 'foto');
                    $fotoUrl = $fotoRaw ? (str_starts_with($fotoRaw, 'http') ? $fotoRaw : asset($fotoRaw)) : asset('images/avatar-placeholder.jpg');

                    // Armar Nombre Completo
                    $nombreCompleto = trim(
                        data_get($empleado, 'nombre', '') . ' ' . 
                        data_get($empleado, 'apellido_paterno', '') . ' ' . 
                        data_get($empleado, 'apellido_materno', '')
                    );

                    // Paquete de datos para el Modal
                    $datosModal = [
                        'id'               => data_get($empleado, 'id'),
                        'nombre_completo'  => $nombreCompleto ?: 'Sin nombre',
                        'nombre'           => data_get($empleado, 'nombre', '—'),
                        'apellido_paterno' => data_get($empleado, 'apellido_paterno', '—'),
                        'apellido_materno' => data_get($empleado, 'apellido_materno', '—'),
                        'telefono'         => data_get($empleado, 'telefono', '—'),
                        'correo'           => data_get($empleado, 'correo', '—'),
                        'usuario'          => data_get($empleado, 'usuario', '—'),
                        'rol'              => data_get($empleado, 'rol', 'Empleado'),
                        'municipio'        => data_get($empleado, 'municipio', '—'),
                        'estatus'          => data_get($empleado, 'estatus', 'Activo'),
                        'foto'             => $fotoUrl,
                    ];
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors">
                    {{-- ID Discreto --}}
                    <td class="px-4 py-3 font-semibold text-gray-400">#{{ data_get($empleado, 'id') }}</td>

                    {{-- Avatar + Nombre Completo + Usuario --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $fotoUrl }}" alt="Foto empleado" class="h-10 w-10 rounded-full object-cover border border-gray-200 flex-shrink-0 shadow-sm bg-gray-100">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $nombreCompleto }}</p>
                                <p class="text-xs text-gray-500">@<span>{{ data_get($empleado, 'usuario', '—') }}</span></p>
                            </div>
                        </div>
                    </td>

                    {{-- Teléfono y Correo juntos --}}
                    <td class="px-4 py-3">
                        <div class="flex flex-col">
                            <span class="text-gray-900 font-medium">{{ data_get($empleado, 'telefono', '—') }}</span>
                            <span class="text-xs text-gray-500 break-all">{{ data_get($empleado, 'correo', '—') }}</span>
                        </div>
                    </td>

                    {{-- Rol con distintivo visual --}}
                    <td class="px-4 py-3">
                        @php $rol = data_get($empleado, 'rol', 'Empleado'); @endphp
                        @if ($rol === 'Administrador')
                            <span class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-semibold text-purple-700 ring-1 ring-inset ring-purple-700/10">Administrador</span>
                        @elseif ($rol === 'Encargado')
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-700/10">Encargado</span>
                        @else
                            <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">{{ $rol }}</span>
                        @endif
                    </td>

                    {{-- Municipio --}}
                    <td class="px-4 py-3 text-gray-700 font-medium">{{ data_get($empleado, 'municipio', '—') }}</td>

                    {{-- Estatus --}}
                    <td class="px-4 py-3">
                        @if (data_get($empleado, 'estatus', 'Activo') === 'Activo')
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
                                    data-empleado='@json($datosModal)'
                                    onclick="abrirModalEmpleado(this)" 
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition-colors" 
                                    title="Ver expediente completo">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            {{-- Ícono EDITAR (Lápiz) --}}
                            <a href="{{ url('/empleado/editar/' . data_get($empleado, 'id')) }}" 
                               class="p-1.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors" 
                               title="Editar datos de empleado">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- Ícono ELIMINAR (Basura) --}}
                            <a href="{{ url('/empleado/mostrar/' . data_get($empleado, 'id')) }}" 
                               class="p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors" 
                               title="Eliminar empleado">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </a>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">No hay empleados registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal de Expediente del Empleado --}}
<div id="modalEmpleado" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl transition-all">
        
        {{-- Header --}}
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Expediente de Empleado</h3>
                <span id="modal-id" class="text-xs text-gray-400"></span>
            </div>
            <button onclick="cerrarModalEmpleado()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-900">
                ✕
            </button>
        </div>

        {{-- Tarjeta Perfil (Foto + Nombre + Usuario + Rol) --}}
        <div class="my-4 flex items-center gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
            <img id="modal-foto" src="" class="h-16 w-16 rounded-full object-cover border-2 border-white shadow-md bg-white flex-shrink-0">
            <div class="space-y-1">
                <h4 id="modal-nombre-completo" class="font-bold text-gray-900 text-base leading-tight"></h4>
                <p class="text-xs text-gray-500 font-mono">@<span id="modal-usuario"></span></p>
                <div class="pt-1 flex items-center gap-2">
                    <span id="modal-rol-badge"></span>
                    <span id="modal-estatus-badge"></span>
                </div>
            </div>
        </div>

        {{-- Detalles de Contacto y Ubicación --}}
        <div class="space-y-3 text-sm">
            
            <div class="bg-blue-50/60 p-3.5 rounded-xl border border-blue-100 space-y-2">
                <span class="block text-xs font-semibold text-blue-700 uppercase tracking-wider">Contacto</span>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="block text-xs text-gray-500">Teléfono</span>
                        <strong id="modal-telefono" class="text-gray-900"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Correo Electrónico</span>
                        <strong id="modal-correo" class="text-gray-900 break-all"></strong>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 space-y-2">
                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Ubicación</span>
                <div>
                    <span class="block text-xs text-gray-500">Municipio</span>
                    <strong id="modal-municipio" class="text-gray-900"></strong>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="mt-6 flex justify-end">
            <button onclick="cerrarModalEmpleado()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    function abrirModalEmpleado(btn) {
        // Lee los datos JSON de forma segura
        const e = JSON.parse(btn.getAttribute('data-empleado'));

        // Cargar datos en el modal
        document.getElementById('modal-id').innerText = 'ID Registro: #' + (e.id || 'N/A');
        document.getElementById('modal-nombre-completo').innerText = e.nombre_completo || 'Empleado';
        document.getElementById('modal-usuario').innerText = e.usuario || '—';
        document.getElementById('modal-telefono').innerText = e.telefono || '—';
        document.getElementById('modal-correo').innerText = e.correo || '—';
        document.getElementById('modal-municipio').innerText = e.municipio || '—';
        document.getElementById('modal-foto').src = e.foto;

        // Badge de Rol
        const rolContenedor = document.getElementById('modal-rol-badge');
        if (e.rol === 'Administrador') {
            rolContenedor.innerHTML = '<span class="px-2 py-0.5 text-xs font-semibold bg-purple-100 text-purple-700 rounded-md">Administrador</span>';
        } else if (e.rol === 'Encargado') {
            rolContenedor.innerHTML = '<span class="px-2 py-0.5 text-xs font-semibold bg-blue-100 text-blue-700 rounded-md">Encargado</span>';
        } else {
            rolContenedor.innerHTML = '<span class="px-2 py-0.5 text-xs font-semibold bg-gray-200 text-gray-700 rounded-md">' + (e.rol || 'Empleado') + '</span>';
        }

        // Badge de Estatus
        const estatusContenedor = document.getElementById('modal-estatus-badge');
        if (e.estatus === 'Activo') {
            estatusContenedor.innerHTML = '<span class="px-2 py-0.5 text-xs font-semibold bg-green-100 text-green-700 rounded-md">Activo</span>';
        } else {
            estatusContenedor.innerHTML = '<span class="px-2 py-0.5 text-xs font-semibold bg-red-100 text-red-700 rounded-md">Inactivo</span>';
        }

        // Mostrar Modal
        document.getElementById('modalEmpleado').classList.remove('hidden');
    }

    function cerrarModalEmpleado() {
        document.getElementById('modalEmpleado').classList.add('hidden');
    }
</script>

@endsection