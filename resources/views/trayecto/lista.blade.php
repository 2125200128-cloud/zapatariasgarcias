@extends('/plantilla/base')

@section('dinamico')

{{-- Título de página / Imagen y Nombre --}}
<div class="flex flex-wrap items-center justify-between gap-2 mb-10 mt-4 px-4">
    <div class="flex items-center gap-4" class="shrink-0 transition-transform hover:scale-105">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/ruta.png') }}" alt="trayectos" class="w-18 h-18 object-contain">
        </a>
        <div>
            <h1 class="text-4xl font-serif text-brand-dark px-4">Lista de Trayectos</h1>
            <p class="font-serif text-[#715b49] px-4">Gestiona y supervisa tus rutas en tiempo real</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($trayectos ?? []) }} registros</span>
        @if ($puedeGestionar ?? false)
            <a href="{{ url('/pedido/pendientes') }}" class="bg-brand-black-coffe text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-brand-caramel transition-colors shadow-sm">
                Pedidos pendientes de aceptar
            </a>
        @endif
        <a href="{{ url('/trayecto/flota') }}" class="border border-gray-300 bg-white text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors shadow-sm">
            Ver mapa de flota
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

{{-- Formulario de Buscador / Filtros --}}
<form method="GET" action="{{ url('/producto') }}" class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4 mb-6">
    <div class="flex flex-wrap items-end gap-3">

        {{-- Filtro Marca --}}
        <div class="flex-1 min-w-[220px]">
            <label for="marca" class="block text-xs font-semibold text-[#3d3228] mb-1">Marca</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-brand-black-coffe/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" name="marca" id="marca" value="{{ request('marca') }}"
                    class="ps-9 px-3 py-2 bg-white border text-brand-black-coffe/60 rounded-lg text-[#17181d] text-sm focus:ring-2 focus:ring-[#715b49] focus:border-[#715b49] block w-full placeholder:text-gray-400 shadow-sm"
                    placeholder="Buscar marca...">
            </div>
        </div>

        {{-- Filtro Proveedor --}}
        <div class="min-w-[160px]">
            <label for="proveedor" class="block text-xs font-semibold text-[#3d3228] mb-1">Proveedor</label>
            <input type="text" name="proveedor" id="proveedor" value="{{ request('proveedor') }}"
                class="px-3 py-2 bg-white border text-brand-black-coffe/60 rounded-lg text-[#17181d] text-sm focus:ring-2 focus:ring-[#715b49] focus:border-[#715b49] block w-full placeholder:text-gray-400 shadow-sm"
                placeholder="Buscar proveedor...">
        </div>

        {{-- Filtro Estatus --}}
        <div class="min-w-[160px]">
            <label for="estatus" class="block text-xs font-semibold text-[#3d3228] mb-1">Estatus</label>
            <select name="estatus" id="estatus"
                class="px-3 py-2 bg-white border border-brand-black-coffe/20 rounded-lg text-[#17181d] text-sm focus:ring-2 focus:ring-[#715b49] focus:border-[#715b49] block w-full shadow-sm">
                <option value="">Todos</option>
                <option value="Activo" @selected(request('estatus') === 'Activo')>Activo</option>
                <option value="Agotado" @selected(request('estatus') === 'Agotado')>Agotado</option>
            </select>
        </div>

        {{-- Botones Filtrar / Limpiar --}}
        <div class="flex gap-2">
            <button type="submit"
                class="inline-flex items-center justify-center bg-brand-black-coffe hover:bg-[#3d3228] text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                Filtrar
            </button>
            @if (request('marca') || request('proveedor') || request('estatus'))
                <a href="{{ url('/producto') }}"
                    class="inline-flex items-center justify-center border border-brand-black-coffe/30 text-brand-black-coffe hover:bg-brand-black-coffe/10 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Limpiar
                </a>
            @endif
        </div>
    </div>
</form>

{{-- Tabla Principal --}}
<div class="bg-white rounded-lg shadow-sm overflow-x-auto border border-gray-100">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Chofer</th>
                <th class="px-4 py-3">Carro</th>
                <th class="px-4 py-3">Pedido</th>
                <th class="px-4 py-3 w-56">Progreso</th>
                <th class="px-4 py-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @php $pasosTrayecto = ['Pendiente', 'Aceptado', 'En ruta', 'Entregado']; @endphp
            @forelse ($trayectos ?? [] as $trayecto)
                @php
                    // Lógica original de cálculo de sucursal
                    $sucursalDestinoIdTrayecto = data_get($trayecto, 'pedido.empleado.sucursales.0.id');
                    $esMiSucursalTrayecto = $sucursalDestinoIdTrayecto && $sucursalDestinoIdTrayecto == data_get($apiUser ?? null, 'sucursal.id');

                    // Nombres y placas formateados
                    $nombreChofer = trim(data_get($trayecto, 'chofer.nombre', '—') . ' ' . data_get($trayecto, 'chofer.apellido', ''));
                    if (empty($nombreChofer)) { $nombreChofer = '—'; }
                    $carroPlacas   = data_get($trayecto, 'carro.placas', '—');
                    $pedidoId      = data_get($trayecto, 'pedido_id', '—');
                    $estatusActual = data_get($trayecto, 'estatus', 'Pendiente');

                    // Paquete de datos para el Modal
                    $datosModal = [
                        'id'       => data_get($trayecto, 'id', '—'),
                        'chofer'   => $nombreChofer,
                        'carro'    => $carroPlacas,
                        'pedido'   => '#' . $pedidoId,
                        'estatus'  => $estatusActual,
                    ];
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors">
                    
                    {{-- ID Discreto --}}
                    <td class="px-4 py-3 font-semibold text-gray-400">#{{ data_get($trayecto, 'id', '—') }}</td>

                    {{-- Chofer --}}
                    <td class="px-4 py-3 font-medium text-gray-800 capitalize">
                        {{ $nombreChofer }}
                    </td>

                    {{-- Carro --}}
                    <td class="px-4 py-3 font-mono text-gray-700">
                        <span class="inline-block px-2 py-0.5 rounded bg-gray-100 border border-gray-200 text-xs font-semibold">
                            {{ $carroPlacas }}
                        </span>
                    </td>

                    {{-- Pedido --}}
                    <td class="px-4 py-3 font-semibold text-blue-600">
                        #{{ $pedidoId }}
                    </td>

                    {{-- Progreso / Estatus --}}
                    <td class="px-4 py-3">
                        @if ($estatusActual === 'Cancelado')
                            <span class="inline-flex items-center gap-1.5 text-red-700 bg-red-50 border border-red-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancelado
                            </span>
                        @else
                            @php
                                $pasoActual = array_search($estatusActual, $pasosTrayecto);
                                $pasoActual = $pasoActual === false ? 0 : $pasoActual;
                            @endphp
                            <div class="w-48">
                                <div class="flex items-center">
                                    @foreach ($pasosTrayecto as $i => $paso)
                                        <div class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 text-[10px] font-bold leading-none transition-colors
                                            {{ $i <= $pasoActual ? 'bg-blue-600 text-white shadow-sm' : 'bg-white border-2 border-gray-300 text-transparent' }}">
                                            @if ($i <= $pasoActual)
                                                ✓
                                            @endif
                                        </div>
                                        @if (!$loop->last)
                                            <div class="flex-1 h-0.5 {{ $i < $pasoActual ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="flex mt-1">
                                    @foreach ($pasosTrayecto as $i => $paso)
                                        <span class="flex-1 text-center text-[9px] leading-tight {{ $i === $pasoActual ? 'font-bold text-blue-600' : 'text-gray-400' }}">
                                            {{ $paso }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </td>

                    {{-- Acciones --}}
                    <td class="px-4 py-3 text-center whitespace-nowrap">
                        <div class="flex items-center justify-center gap-2 text-sm">
                            
                            {{-- Ícono VER (Detalle Modal) --}}
                            <button type="button" 
                                    data-item='@json($datosModal)'
                                    onclick="abrirModalTrayecto(this)" 
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition-colors" 
                                    title="Ver detalle de trayecto">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            {{-- Editar (Si puede gestionar) --}}
                            @if ($puedeGestionar ?? false)
                                <a href="{{ url('/trayecto/editar/' . data_get($trayecto, 'id', '')) }}" class="text-blue-600 hover:underline font-medium">
                                    Editar
                                </a>
                            @endif

                            {{-- Ver nota (PDF) --}}
                            <a href="{{ url('/pedido/' . data_get($trayecto, 'pedido_id', '') . '/pdf') }}" class="text-gray-700 hover:underline font-medium" target="_blank">
                                Ver nota
                            </a>

                            {{-- Enviar ubicación al chofer --}}
                            @if ($puedeGestionar ?? false)
                                @php
                                    $urlCompartir = \Illuminate\Support\Facades\URL::temporarySignedRoute(
                                        'trayecto.compartir',
                                        now()->addHours(48),
                                        ['id' => data_get($trayecto, 'id', '')]
                                    );
                                @endphp
                                <button type="button" class="copiar-link text-emerald-600 hover:underline font-medium" data-link="{{ $urlCompartir }}">
                                    Enviar ubicación al chofer
                                </button>
                            @endif

                            {{-- Formulario Confirmar Llegada --}}
                            @if ($estatusActual === 'En ruta' && (($esAdmin ?? false) || $esMiSucursalTrayecto))
                                <form action="{{ url('/trayecto/' . data_get($trayecto, 'id', '') . '/confirmar-llegada') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 px-2 py-0.5 rounded text-xs font-semibold transition-colors">
                                        Llegó
                                    </button>
                                </form>
                            @endif

                            {{-- Cancelar --}}
                            @if (($puedeGestionar ?? false) && !in_array($estatusActual, ['Cancelado', 'Entregado']))
                                <a href="{{ url('/trayecto/mostrar/' . data_get($trayecto, 'id', '')) }}" class="text-red-600 hover:underline font-medium">
                                    Cancelar
                                </a>
                            @endif

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                        No hay trayectos registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal de Detalle de Trayecto --}}
<div id="modalTrayecto" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transition-all">
        
        {{-- Header Modal --}}
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Detalle del Trayecto</h3>
                <span id="modal-id" class="text-xs text-gray-400"></span>
            </div>
            <button onclick="cerrarModalTrayecto()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-900">
                ✕
            </button>
        </div>

        {{-- Contenido del Modal --}}
        <div class="mt-4 space-y-3 text-sm">
            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 space-y-2">
                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Asignación</span>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="block text-xs text-gray-500">Chofer</span>
                        <strong id="modal-chofer" class="text-gray-900"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Vehículo (Placas)</span>
                        <strong id="modal-carro" class="text-gray-900 font-mono"></strong>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50/60 p-3.5 rounded-xl border border-blue-100 space-y-2">
                <span class="block text-xs font-semibold text-blue-700 uppercase tracking-wider">Pedido & Estado</span>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="block text-xs text-gray-500">Pedido ID</span>
                        <strong id="modal-pedido" class="text-blue-600 font-bold text-base"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Estatus Actual</span>
                        <span id="modal-estatus" class="block pt-1"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Modal --}}
        <div class="mt-6 flex justify-end">
            <button onclick="cerrarModalTrayecto()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    function abrirModalTrayecto(btn) {
        const item = JSON.parse(btn.getAttribute('data-item'));

        document.getElementById('modal-id').innerText = 'ID Trayecto: #' + (item.id || 'N/A');
        document.getElementById('modal-chofer').innerText = item.chofer || '—';
        document.getElementById('modal-carro').innerText = item.carro || '—';
        document.getElementById('modal-pedido').innerText = item.pedido || '—';

        const contenedorEstatus = document.getElementById('modal-estatus');
        if (item.estatus === 'Cancelado') {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20">Cancelado</span>';
        } else if (item.estatus === 'Entregado') {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">Entregado</span>';
        } else {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-semibold text-blue-700 ring-1 ring-inset ring-blue-600/20">' + item.estatus + '</span>';
        }

        document.getElementById('modalTrayecto').classList.remove('hidden');
    }

    function cerrarModalTrayecto() {
        document.getElementById('modalTrayecto').classList.add('hidden');
    }

    document.querySelectorAll('.copiar-link').forEach((boton) => {
        const textoOriginal = boton.textContent;
        boton.addEventListener('click', () => {
            navigator.clipboard.writeText(boton.dataset.link).then(() => {
                boton.textContent = '¡Link copiado!';
                setTimeout(() => { boton.textContent = textoOriginal; }, 2000);
            }).catch(() => {
                alert('No se pudo copiar el link. Cópialo manualmente:\n\n' + boton.dataset.link);
            });
        });
    });
</script>

@endsection