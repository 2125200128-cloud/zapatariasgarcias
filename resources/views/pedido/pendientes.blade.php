@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Pedidos pendientes de aceptar</h1>
    <a href="{{ url('/pedido') }}" class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
        Ver todos los pedidos
    </a>
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
                <th class="px-4 py-3">Sucursal</th>
                <th class="px-4 py-3">Fecha</th>
                <th class="px-4 py-3">Productos</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($pedidos ?? [] as $pedido)
                <tr>
                    <td class="px-4 py-3">{{ $pedido->id }}</td>
                    <td class="px-4 py-3">{{ optional($pedido->empleado?->sucursales->first())->nombre ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $pedido->fecha }}</td>
                    <td class="px-4 py-3">
                        <ul class="space-y-0.5">
                            @foreach ($pedido->detallePedidos as $detalle)
                                <li>{{ $detalle->producto->nombre ?? '—' }} ({{ $detalle->producto->talla ?? '—' }}) × {{ $detalle->cantidad_solicitada }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <a href="{{ url('/pedido/' . $pedido->id . '/aceptar') }}" class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-blue-700">
                            Aceptar y asignar
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">No hay pedidos pendientes de aceptar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
