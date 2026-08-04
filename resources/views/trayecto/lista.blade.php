@extends('/plantilla/base')

@section('dinamico')

@php
    $empleadoLista = \App\Models\Empleado::auth();
    $puedeAsignarLista = $empleadoLista && ($empleadoLista->esAdministrador() || $empleadoLista->esMatriz());
@endphp

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Lista de trayectos</h1>
    <div class="flex gap-2">
        @if ($puedeAsignarLista)
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
    <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
        {{ session('error') }}
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
            @forelse ($trayectos as $trayecto)
                @php
                    $sucursalDestinoFila = optional($trayecto->pedido?->empleado)->sucursales->first();
                    $esMiSucursalFila = $sucursalDestinoFila && $empleadoLista && optional($empleadoLista->miSucursal())->id === $sucursalDestinoFila->id;
                @endphp
                <tr>
                    <td class="px-4 py-3">{{ $trayecto->id }}</td>
                    <td class="px-4 py-3">{{ $trayecto->chofer ? $trayecto->chofer->nombre . ' ' . $trayecto->chofer->apellido : '—' }}</td>
                    <td class="px-4 py-3">{{ $trayecto->carro ? $trayecto->carro->placas : '—' }}</td>
                    <td class="px-4 py-3">#{{ $trayecto->pedido_id }}</td>
                    <td class="px-4 py-3">
                        @if ($trayecto->estatus === 'Cancelado')
                            <span class="inline-flex items-center gap-1 text-red-700 bg-red-50 border border-red-200 px-2 py-1 rounded-full text-xs font-medium">
                                ✕ Cancelado
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
                        @if ($puedeAsignarLista)
                            <a href="{{ url('/trayecto/editar/' . $trayecto->id) }}" class="text-blue-600 hover:underline text-sm">
                                Editar
                            </a>
                        @endif
                        <a href="{{ url('/pedido/' . $trayecto->pedido_id . '/pdf') }}" class="text-gray-700 hover:underline text-sm" target="_blank">
                            Ver nota
                        </a>
                        @if ($puedeAsignarLista)
                            <a href="{{ URL::temporarySignedRoute('trayecto.compartir', now()->addHours(48), ['id' => $trayecto->id]) }}" class="text-green-700 hover:underline text-sm" target="_blank">
                                Link para el chofer
                            </a>
                        @endif
                        @if ($trayecto->estatus === 'En ruta' && (($empleadoLista && $empleadoLista->esAdministrador()) || $esMiSucursalFila))
                            <form action="{{ url('/trayecto/' . $trayecto->id . '/llegada') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-emerald-700 hover:underline text-sm font-medium">Llegó</button>
                            </form>
                        @endif
                        @if ($puedeAsignarLista && $trayecto->estatus !== 'Cancelado' && $trayecto->estatus !== 'Entregado')
                            <a href="{{ url('/trayecto/mostrar/' . $trayecto->id) }}" class="text-red-600 hover:underline text-sm">
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
