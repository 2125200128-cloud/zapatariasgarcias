@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold">Lista de trayectos</h1>
    <a href="{{ url('/trayecto/flota') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm">
        Ver mapa de flota
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Chofer</th>
                <th class="px-4 py-3">Carro</th>
                <th class="px-4 py-3">Pedido</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($trayectos as $trayecto)
                <tr class="border-t">
                    <td class="px-4 py-3">{{ $trayecto->id }}</td>
                    <td class="px-4 py-3">{{ $trayecto->chofer ? $trayecto->chofer->nombre . ' ' . $trayecto->chofer->apellido : '—' }}</td>
                    <td class="px-4 py-3">{{ $trayecto->carro ? $trayecto->carro->placas : '—' }}</td>
                    <td class="px-4 py-3">#{{ $trayecto->pedido_id }}</td>
                    <td class="px-4 py-3">{{ $trayecto->estatus }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ url('/trayecto/' . $trayecto->id . '/compartir') }}" class="text-blue-600 hover:underline" target="_blank">
                            Compartir ubicación
                        </a>
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
