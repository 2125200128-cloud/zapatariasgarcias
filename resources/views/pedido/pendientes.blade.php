@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <img src="{{ asset('images/pendientes.png') }}" alt="Pedidos-pendientes" class="w-15 h-15 m-6">
        <h1 class="text-3xl font-serif text-[#17181d]">Pedidos pendientes de aceptar</h1>
    </div>
    <div>
        <a href="{{ url('/pedido') }}" class="bg-brand-black-coffe text-white px-4 py-2 rounded-lg text-sm hover:bg-brand-brown-dark font-semibold">
            Ver todos los pedidos
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
                <th class="px-4 py-3">Sucursal</th>
                <th class="px-4 py-3">Fecha</th>
                <th class="px-4 py-3">Productos</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($pedidos ?? [] as $pedido)
                <tr>
                    <td class="px-4 py-3">{{ data_get($pedido, 'id', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($pedido, 'sucursal.nombre', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($pedido, 'fecha', '—') }}</td>
                    <td class="px-4 py-3">
                        <ul class="space-y-0.5">
                            @forelse (data_get($pedido, 'detallePedidos', []) as $detalle)
                                <li>
                                    {{ data_get($detalle, 'producto.nombre', '—') }}
                                    ({{ data_get($detalle, 'producto.talla', '—') }})
                                    × {{ data_get($detalle, 'cantidad_solicitada', '—') }}
                                </li>
                            @empty
                                <li class="text-gray-500">Sin productos</li>
                            @endforelse
                        </ul>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <a href="{{ url('/pedido/' . data_get($pedido, 'id', '') . '/aceptar') }}" 
                           class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-blue-700">
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
