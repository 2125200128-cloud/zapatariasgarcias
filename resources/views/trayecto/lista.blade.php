@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">{{ $puedeGestionar ? 'Lista de trayectos' : 'Mis trayectos' }}</h1>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($trayectos ?? []) }} registros</span>
        @if ($puedeGestionar)
            <a href="{{ url('/pedido/pendientes') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
                Pedidos pendientes de aceptar
            </a>
        @endif
        <a href="{{ url('/trayecto/flota') }}" class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
            Ver mapa de flota
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

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Chofer</th>
                <th class="px-4 py-3">Carro</th>
                <th class="px-4 py-3">Pedido</th>
                <th class="px-4 py-3 w-56">Progreso</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @php $pasosTrayecto = ['Pendiente', 'Aceptado', 'En ruta', 'Entregado']; @endphp
            @forelse ($trayectos ?? [] as $trayecto)
                @php
                    $sucursalDestinoIdTrayecto = data_get($trayecto, 'pedido.empleado.sucursales.0.id');
                    $esMiSucursalTrayecto = $sucursalDestinoIdTrayecto && $sucursalDestinoIdTrayecto == data_get($apiUser, 'sucursal.id');
                @endphp
                <tr>
                    <td class="px-4 py-3">{{ data_get($trayecto, 'id', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($trayecto, 'chofer.nombre', '—') }} {{ data_get($trayecto, 'chofer.apellido', '') }}</td>
                    <td class="px-4 py-3">{{ data_get($trayecto, 'carro.placas', '—') }}</td>
                    <td class="px-4 py-3">#{{ data_get($trayecto, 'pedido_id', '—') }}</td>
                    <td class="px-4 py-3">
                        @if (data_get($trayecto, 'estatus') === 'Cancelado')
                            <span class="inline-flex items-center gap-1 text-red-700 bg-red-50 border border-red-200 px-2 py-1 rounded-full text-xs font-medium">
                                ✕ Cancelado
                            </span>
                        @else
                            @php
                                $pasoActual = array_search(data_get($trayecto, 'estatus'), $pasosTrayecto);
                                $pasoActual = $pasoActual === false ? 0 : $pasoActual;
                            @endphp
                            <div class="w-48">
                                <div class="flex items-center">
                                    @foreach ($pasosTrayecto as $i => $paso)
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
                                    @foreach ($pasosTrayecto as $i => $paso)
                                        <span class="flex-1 text-center text-[9px] leading-tight {{ $i === $pasoActual ? 'font-semibold text-gray-800' : 'text-gray-400' }}">
                                            {{ $paso }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        @if ($puedeGestionar)
                            <a href="{{ url('/trayecto/editar/' . data_get($trayecto, 'id', '')) }}" class="text-blue-600 hover:underline text-sm">Editar</a>
                        @endif
                        <a href="{{ url('/pedido/' . data_get($trayecto, 'pedido_id', '') . '/pdf') }}" class="text-gray-700 hover:underline text-sm" target="_blank">
                            Ver nota
                        </a>
                        @if ($puedeGestionar)
                            <a href="{{ url('/trayecto/' . data_get($trayecto, 'id', '') . '/compartir') }}" class="text-green-700 hover:underline text-sm" target="_blank">
                                Enviar ubicación al chofer
                            </a>
                        @endif
                        @if (data_get($trayecto, 'estatus') === 'En ruta' && ($esAdmin || $esMiSucursalTrayecto))
                            <form action="{{ url('/trayecto/' . data_get($trayecto, 'id', '') . '/confirmar-llegada') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-emerald-700 hover:underline text-sm font-medium">Llegó</button>
                            </form>
                        @endif
                        @if ($puedeGestionar && !in_array(data_get($trayecto, 'estatus'), ['Cancelado', 'Entregado']))
                            <a href="{{ url('/trayecto/mostrar/' . data_get($trayecto, 'id', '')) }}" class="text-red-600 hover:underline text-sm">
                                Cancelar
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay trayectos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection