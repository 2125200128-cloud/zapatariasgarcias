@extends('/plantilla/base')

@section('dinamico')

@php
    $empleadoPedidos = \App\Models\Empleado::auth();
    $puedeAsignarPedidos = $empleadoPedidos && ($empleadoPedidos->esAdministrador() || $empleadoPedidos->esMatriz());
@endphp

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Pedidos</h1>
    <div class="flex gap-2">
        @if ($puedeAsignarPedidos)
            <a href="{{ url('/pedido/pendientes') }}" class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
                Pedidos pendientes de aceptar
            </a>
        @endif
        <a href="{{ url('/pedido/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Nuevo pedido
        </a>
    </div>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Sucursal</th>
                <th class="px-4 py-3">Fecha</th>
                <th class="px-4 py-3"># Productos</th>
                <th class="px-4 py-3 w-56">Progreso</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($pedidos ?? [] as $pedido)
                @php
                    $trayecto = $pedido->trayectos->first();
                    $sucursalPedido = optional($pedido->empleado)->sucursales->first();
                    $esMiSucursal = $sucursalPedido && $empleadoPedidos && optional($empleadoPedidos->miSucursal())->id === $sucursalPedido->id;
                @endphp
                <tr>
                    <td class="px-4 py-3">{{ $pedido->id }}</td>
                    <td class="px-4 py-3">{{ optional($pedido->empleado?->sucursales->first())->nombre ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $pedido->fecha }}</td>
                    <td class="px-4 py-3">{{ $pedido->detallePedidos->count() }}</td>
                    <td class="px-4 py-3">
                        @if ($pedido->estatus === 'Cancelado')
                            <span class="inline-flex items-center gap-1 text-red-700 bg-red-50 border border-red-200 px-2 py-1 rounded-full text-xs font-medium">
                                ✕ Cancelado
                            </span>
                        @elseif (!$trayecto)
                            <span class="inline-flex items-center gap-1 text-amber-700 bg-amber-50 border border-amber-200 px-2 py-1 rounded-full text-xs font-medium">
                                Esperando aceptación
                            </span>
                        @else
                            @php
                                $pasos = ['Pendiente', 'Aceptado', 'En ruta', 'Entregado'];
                                $pasoActual = array_search($trayecto->estatus, $pasos);
                                $pasoActual = $pasoActual === false ? 0 : $pasoActual;
                            @endphp
                            <div class="w-48">
                                <div class="flex items-center">
                                    @foreach ($pasos as $i => $paso)
                                        <div class="w-5 h-5 rounded-full flex items-center justify-center shrink-0 text-[10px] leading-none
                                            {{ $i <= $pasoActual ? 'bg-blue-600 text-white' : 'bg-white border-2 border-gray-300' }}">
                                            @if ($i <= $pasoActual)
                                                &#10003;
                                            @endif
                                        </div>
                                        @if (!$loop->last)
                                            <div class="flex-1 h-0.5 {{ $i < $pasoActual ? 'bg-blue-600' : 'bg-gray-300' }}"></div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="flex mt-1">
                                    @foreach ($pasos as $i => $paso)
                                        <span class="flex-1 text-center text-[9px] leading-tight {{ $i === $pasoActual ? 'font-semibold text-gray-800' : 'text-gray-400' }}">
                                            {{ $paso }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        @if ($puedeAsignarPedidos)
                            <a href="{{ url('/pedido/editar/' . $pedido->id) }}" class="text-blue-600 hover:underline">Editar</a>
                        @endif
                        <a href="{{ url('/pedido/' . $pedido->id . '/pdf') }}" class="text-green-700 hover:underline" target="_blank">Ver nota</a>
                        @if ($trayecto && $trayecto->estatus === 'En ruta' && ($empleadoPedidos && $empleadoPedidos->esAdministrador() || $esMiSucursal))
                            <form action="{{ url('/trayecto/' . $trayecto->id . '/llegada') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-emerald-700 hover:underline font-medium">Llegó</button>
                            </form>
                        @endif
                        @if ($puedeAsignarPedidos)
                            <a href="{{ url('/pedido/mostrar/' . $pedido->id) }}" class="text-red-600 hover:underline">Eliminar</a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay pedidos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
