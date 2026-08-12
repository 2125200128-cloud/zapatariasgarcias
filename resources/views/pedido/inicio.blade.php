@extends('/plantilla/base')

@section('dinamico')

{{-- Título de página / Imagen y Nombre --}}
<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div class="flex items-center gap-4">
        <a href="{{ url('/') }}" class="shrink-0 transition-transform hover:scale-105">
            <img src="{{ asset('images/pedidos-formulario.png') }}" alt="Inicio-pedidos" class="w-16 h-16 object-contain">
        </a>
        <div>
            <h1 class="text-3xl font-serif text-[#17181d] font-bold">Pedidos</h1>
            <p class="font-serif text-[#715b49] text-sm">Gestiona y supervisa los pedidos realizados en las sucursales</p>
        </div>
    </div>
    
    <div class="flex flex-wrap items-center gap-3">
        @if ($puedeGestionar ?? false)
            <a href="{{ url('/pedido/pendientes') }}" class="border border-[#715b49]/30 text-[#715b49] hover:bg-[#715b49]/10 px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                Pedidos pendientes de aceptar
            </a>
        @endif
        <span class="text-sm font-medium text-[#3d3228] bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200">
            {{ count($pedidos ?? []) }} registros
        </span>
        <a href="{{ url('/pedido/formulario') }}" class="bg-[#17181d] hover:bg-[#3d3228] text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
            + Nuevo pedido
        </a>
    </div>
</div>

{{-- Formulario de Buscador / Filtros --}}
<form method="GET" action="{{ url('/pedido') }}" class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4 mb-6">
    <div class="flex flex-wrap items-end gap-3">
        
        {{-- Buscador por sucursal --}}
        <div class="flex-1 min-w-[220px]">
            <label for="busqueda" class="block text-xs font-semibold text-[#3d3228] mb-1">Sucursal</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-[#715b49]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" name="busqueda" id="busqueda" value="{{ request('busqueda') }}"
                    class="ps-9 px-3 py-2 bg-white border border-[#715b49]/20 rounded-lg text-[#17181d] text-sm focus:ring-2 focus:ring-[#715b49] focus:border-[#715b49] block w-full placeholder:text-gray-400 shadow-sm"
                    placeholder="Buscar sucursal...">
            </div>
        </div>

        {{-- Filtro Fecha --}}
        <div class="min-w-[160px]">
            <label for="fecha" class="block text-xs font-semibold text-[#3d3228] mb-1">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="{{ request('fecha') }}"
                class="px-3 py-2 bg-white border border-[#715b49]/20 rounded-lg text-[#17181d] text-sm focus:ring-2 focus:ring-[#715b49] focus:border-[#715b49] block w-full shadow-sm">
        </div>

        {{-- Filtro Estatus --}}
        <div class="min-w-[160px]">
            <label for="estatus" class="block text-xs font-semibold text-[#3d3228] mb-1">Estatus</label>
            <select name="estatus" id="estatus"
                class="px-3 py-2 bg-white border border-[#715b49]/20 rounded-lg text-[#17181d] text-sm focus:ring-2 focus:ring-[#715b49] focus:border-[#715b49] block w-full shadow-sm">
                <option value="">Todos</option>
                <option value="Pendiente" @selected(request('estatus') === 'Pendiente')>Pendiente</option>
                <option value="Realizado" @selected(request('estatus') === 'Realizado')>Realizado</option>
                <option value="Cancelado" @selected(request('estatus') === 'Cancelado')>Cancelado</option>
            </select>
        </div>

        {{-- Botones Filtrar / Limpiar --}}
        <div class="flex gap-2">
            <button type="submit"
                class="inline-flex items-center justify-center bg-[#715b49] hover:bg-[#3d3228] text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                Filtrar
            </button>
            @if (request('busqueda') || request('fecha') || request('estatus'))
                <a href="{{ url('/pedido') }}"
                    class="inline-flex items-center justify-center border border-[#715b49]/30 text-[#715b49] hover:bg-[#715b49]/10 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    Limpiar
                </a>
            @endif
        </div>
    </div>
</form>

{{-- Alertas de sesión --}}
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
<div class="bg-white rounded-xl shadow-sm overflow-x-auto border border-gray-200/80">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Sucursal</th>
                <th class="px-4 py-3">Fecha</th>
                <th class="px-4 py-3 text-center"># Productos</th>
                <th class="px-4 py-3 w-56">Progreso</th>
                <th class="px-4 py-3 text-center">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @php $pasosPedido = ['Pendiente', 'Aceptado', 'En ruta', 'Entregado']; @endphp
            @forelse ($pedidos ?? [] as $pedido)
                @php
                    $trayecto = data_get($pedido, 'trayectos.0');
                    $sucursalDestinoId = data_get($pedido, 'sucursal.id');
                    $esMiSucursalPedido = $sucursalDestinoId && $sucursalDestinoId == data_get($apiUser ?? null, 'sucursal.id');

                    $nombreSucursal = data_get($pedido, 'sucursal.nombre', '—');
                    $fechaPedido    = data_get($pedido, 'fecha', '—');
                    $numProductos   = count(data_get($pedido, 'detallePedidos', []));
                    $estatusPedido  = data_get($pedido, 'estatus', 'Pendiente');
                    $estatusTrayecto = data_get($trayecto, 'estatus', null);

                    // Paquete de datos para el Modal
                    $datosModal = [
                        'id'              => data_get($pedido, 'id', '—'),
                        'sucursal'        => $nombreSucursal,
                        'fecha'           => $fechaPedido,
                        'num_productos'   => $numProductos,
                        'estatus_pedido'  => $estatusPedido,
                        'estatus_trayecto'=> $estatusTrayecto,
                        'tiene_trayecto'  => !is_null($trayecto),
                    ];
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors">
                    
                    {{-- ID --}}
                    <td class="px-4 py-3 font-semibold text-gray-400">#{{ data_get($pedido, 'id', '—') }}</td>

                    {{-- Sucursal --}}
                    <td class="px-4 py-3 font-medium text-gray-800">
                        {{ $nombreSucursal }}
                    </td>

                    {{-- Fecha --}}
                    <td class="px-4 py-3 text-gray-600 font-mono text-xs">
                        {{ $fechaPedido }}
                    </td>

                    {{-- # Productos --}}
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center justify-center bg-gray-100 text-gray-800 font-semibold px-2.5 py-0.5 rounded-full text-xs">
                            {{ $numProductos }}
                        </span>
                    </td>

                    {{-- Progreso / Estatus --}}
                    <td class="px-4 py-3">
                        @if ($estatusPedido === 'Cancelado')
                            <span class="inline-flex items-center gap-1 text-red-700 bg-red-50 border border-red-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancelado
                            </span>
                        @elseif (!$trayecto)
                            <span class="inline-flex items-center gap-1.5 text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-1 rounded-full text-xs font-semibold">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                Esperando aceptación
                            </span>
                        @else
                            @php
                                $pasoActual = array_search(data_get($trayecto, 'estatus'), $pasosPedido);
                                $pasoActual = $pasoActual === false ? 0 : $pasoActual;
                            @endphp
                            <div class="w-48">
                                <div class="flex items-center">
                                    @foreach ($pasosPedido as $i => $paso)
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
                                    @foreach ($pasosPedido as $i => $paso)
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
                                    onclick="abrirModalPedido(this)" 
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition-colors" 
                                    title="Ver detalle de pedido">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            {{-- Acciones de gestión --}}
                            @if ($puedeGestionar ?? false)
                                <a href="{{ url('/pedido/editar/' . data_get($pedido, 'id', '')) }}" class="text-blue-600 hover:underline font-medium">
                                    Editar
                                </a>
                                <a href="{{ url('/pedido/mostrar/' . data_get($pedido, 'id', '')) }}" class="text-red-600 hover:underline font-medium">
                                    Eliminar
                                </a>
                            @endif

                            {{-- Documento PDF --}}
                            <a href="{{ url('/pedido/' . data_get($pedido, 'id', '') . '/pdf') }}" target="_blank" class="text-gray-600 hover:underline font-medium">
                                PDF
                            </a>

                            {{-- Botón "Llegó" si está en ruta --}}
                            @if ($trayecto && data_get($trayecto, 'estatus') === 'En ruta' && (($esAdmin ?? false) || $esMiSucursalPedido))
                                <form action="{{ url('/trayecto/' . data_get($trayecto, 'id', '') . '/confirmar-llegada') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 px-2 py-0.5 rounded text-xs font-semibold transition-colors">
                                        Llegó
                                    </button>
                                </form>
                            @endif

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                        No hay pedidos registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal de Detalle de Pedido --}}
<div id="modalPedido" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl transition-all">
        
        {{-- Header Modal --}}
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Detalle del Pedido</h3>
                <span id="modal-id" class="text-xs text-gray-400"></span>
            </div>
            <button onclick="cerrarModalPedido()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-900">
                ✕
            </button>
        </div>

        {{-- Contenido Modal --}}
        <div class="mt-4 space-y-3 text-sm">
            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 space-y-2">
                <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider">Información General</span>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="block text-xs text-gray-500">Sucursal Destino</span>
                        <strong id="modal-sucursal" class="text-gray-900"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Fecha de Registro</span>
                        <span id="modal-fecha" class="text-gray-700 font-mono text-xs"></span>
                    </div>
                </div>
            </div>

            <div class="bg-amber-50/60 p-3.5 rounded-xl border border-amber-100 space-y-2">
                <span class="block text-xs font-semibold text-amber-800 uppercase tracking-wider">Productos & Estado</span>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <span class="block text-xs text-gray-500">Total Productos</span>
                        <strong id="modal-[#productos]" class="text-gray-900 text-base"></strong>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500">Estatus / Progreso</span>
                        <span id="modal-estatus" class="block pt-1"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer Modal --}}
        <div class="mt-6 flex justify-end">
            <button onclick="cerrarModalPedido()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    function abrirModalPedido(btn) {
        const item = JSON.parse(btn.getAttribute('data-item'));

        document.getElementById('modal-id').innerText = 'ID Pedido: #' + (item.id || 'N/A');
        document.getElementById('modal-sucursal').innerText = item.sucursal || '—';
        document.getElementById('modal-fecha').innerText = item.fecha || '—';
        document.getElementById('modal-[#productos]').innerText = item.num_productos + ' ítem(s)';

        const contenedorEstatus = document.getElementById('modal-estatus');
        
        if (item.estatus_pedido === 'Cancelado') {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20">Cancelado</span>';
        } else if (!item.tiene_trayecto) {
            contenedorEstatus.innerHTML = '<span class="inline-flex items-center rounded-full bg-amber-50 px-2.5 py-0.5 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">Esperando aceptación</span>';
        } else {
            const trayEstatus = item.estatus_trayecto || 'Pendiente';
            const colorClass = trayEstatus === 'Entregado' ? 'bg-green-50 text-green-700 ring-green-600/20' : 'bg-blue-50 text-blue-700 ring-blue-600/20';
            contenedorEstatus.innerHTML = `<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset ${colorClass}">${trayEstatus}</span>`;
        }

        document.getElementById('modalPedido').classList.remove('hidden');
    }

    function cerrarModalPedido() {
        document.getElementById('modalPedido').classList.add('hidden');
    }
</script>

@endsection