@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/pendientes-formulario.png') }}" alt="Pedidos-pendientes" class="w-20 h-20 m-6">
        </a>
        <div class="mt-2">
            <h1 class="text-4xl font-serif text-[#17181d]">Pedidos pendientes de aceptar</h1>
            <p class="font-serif text-[#715b49]">Gestiona y supervisa tus pedidos pendientes en tiempo real</p>
        </div>
    </div>
    <div class="flex items-center gap-3">
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

<form method="GET" action="{{ url('/pedido') }}" class="bg-white rounded-lg shadow border border-brand-brown/10 p-4 mb-4">
    <div class="flex flex-wrap items-end gap-3">
        <div class="flex-1 min-w-[220px]">
            <label for="busqueda" class="block text-xs font-semibold text-[#3d3228] mb-1">Pedidos pendientes</label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-brand-brown/60" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" name="busqueda" id="busqueda" value="{{ request('busqueda') }}"
                    class="ps-9 px-3 py-2 bg-white border border-brand-brown/20 rounded-lg text-[#17181d] text-sm focus:ring-2 focus:ring-brand-brown focus:border-brand-brown block w-full placeholder:text-gray-400"
                    placeholder="Buscar sucursal por ID o nombre...">
            </div>
        </div>

        <div class="min-w-[160px]">
            <label for="fecha" class="block text-xs font-semibold text-[#3d3228] mb-1">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="{{ request('fecha') }}"
                class="px-3 py-2 bg-white border border-brand-brown/20 rounded-lg text-[#17181d] text-sm focus:ring-2 focus:ring-brand-brown focus:border-brand-brown block w-full">
        </div>

        <div class="flex gap-2">
            <button type="submit"
                class="inline-flex items-center justify-center bg-brand-caramel hover:bg-brand-caramel-dark text-white px-4 py-2 rounded-lg text-sm font-semibold">
                Filtrar
            </button>
            @if (request('busqueda') || request('fecha') || request('estatus'))
                <a href="{{ url('/pedido') }}"
                    class="inline-flex items-center justify-center border border-brand-brown/30 text-brand-brown hover:bg-brand-brown/10 px-4 py-2 rounded-lg text-sm">
                    Limpiar
                </a>
            @endif
        </div>
    </div>
</form>

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
