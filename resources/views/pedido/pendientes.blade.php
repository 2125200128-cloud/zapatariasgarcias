@extends('/plantilla/base')

@section('dinamico')

<div class="flex flex-wrap items-center justify-between gap-2 mb-10 mt-4 px-4">
    <div class="flex items-center gap-4">
        <a href="{{ url('/') }}">
            <img src="{{ asset('images/pendientes-formulario.png') }}" alt="Pedidos-pendientes" class="w-18 h-18 object-contain">
        </a>
        <div class="mt-2">
            <h1 class="text-4xl font-serif text-[#17181d] px-4">Pedidos pendientes de aceptar</h1>
            <p class="font-serif text-[#715b49] px-4">Gestiona y supervisa tus pedidos pendientes en tiempo real</p>
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

<form method="GET" action="{{ url('/pedido/pendientes') }}" class="bg-white rounded-xl shadow-sm border border-gray-200/80 p-4 mb-4">
    <div class="flex flex-wrap items-end gap-3">
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

        <div class="min-w-[160px]">
            <label for="fecha" class="block text-xs font-semibold text-[#3d3228] mb-1">Fecha</label>
            <input type="date" name="fecha" id="fecha" value="{{ request('fecha') }}"
                class="px-3 py-2 bg-white border border-[#715b49]/20 rounded-lg text-[#17181d] text-sm focus:ring-2 focus:ring-[#715b49] focus:border-[#715b49] block w-full shadow-sm">
        </div>

        <div class="flex gap-2">
            <button type="submit"
                class="inline-flex items-center justify-center bg-[#715b49] hover:bg-[#3d3228] text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors shadow-sm">
                Filtrar
            </button>
            @if (request('busqueda') || request('fecha'))
                <a href="{{ url('/pedido/pendientes') }}"
                    class="inline-flex items-center justify-center border border-[#715b49]/30 text-[#715b49] hover:bg-[#715b49]/10 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
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
                    <td class="px-4 py-3">{{ data_get($pedido, 'empleado.sucursales.0.nombre', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($pedido, 'fecha', '—') }}</td>
                    <td class="px-4 py-3">
                        <ul class="space-y-0.5">
                            @forelse (data_get($pedido, 'detalle_pedidos', []) as $detalle)
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
