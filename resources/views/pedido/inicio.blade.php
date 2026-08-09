@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Pedidos</h1>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($pedidos ?? []) }} registros</span>
        <a href="{{ url('/pedido/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Nuevo pedido
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
                <th class="px-4 py-3">Sucursal</th>
                <th class="px-4 py-3">Fecha</th>
                <th class="px-4 py-3">Productos</th>
                <th class="px-4 py-3">Estatus</th>
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
                    <td class="px-4 py-3">{{ data_get($pedido, 'estatus', '—') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                      @if ($puedeGestionar)
                      <a href="{{ url('/pedido/editar/' . data_get($pedido, 'id', '')) }}" class="text-blue-600 hover:underline">Editar</a>
                      <a href="{{ url('/pedido/mostrar/' . data_get($pedido, 'id', '')) }}" class="text-red-600 hover:underline">Eliminar</a>
                     @endif
                     <a href="{{ url('/pedido/' . data_get($pedido, 'id', '') . '/pdf') }}" target="_blank" class="text-gray-600 hover:underline">PDF</a>
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
