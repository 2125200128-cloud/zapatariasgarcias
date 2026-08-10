@extends('/plantilla/base')

@section('dinamico')

<!-- titulo de página / imagen y nombre -->
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <img src="{{ asset('images/pedidos-negro.png') }}" alt="Inicio-pedidos" class="w-20 h-20 m-6">
        <h1 class="text-4xl font-serif text-[#17181d]">Pedidos</h1>
    </div>
    <div class="flex items-center gap-3">
        <span class="text-sm text-[#3d3228]">{{ count($pedidos ?? []) }} registros</span>
        <a href="{{ url('/pedido/formulario') }}" class="bg-brand-black-coffe text-white px-4 py-2 rounded-lg text-sm hover:bg-brand-brown-dark font-semibold">
            Nuevo pedido
        </a>
    </div>
</div>
<!-- titulo de página -->

<! -- Search branch -->
<form class="flex items-center max-w-sm mb-4 space-x-2">
    <label for="simple-search" class="sr-only">Buscar</label>
    <div class="relative w-full">
        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
            <svg class=" w-4 h-4 text-brand-brown/60" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
            </svg>
        </div>
        <input type="text" id="simple-search"
            class="px-3 py-2.5 bg-white border border-brand-brown/20 rounded-lg ps-9 text-[#17181d] text-sm focus:ring-2 focus:ring-brand-brown focus:border-brand-brown block w-full placeholder:text-gray-400"
            placeholder="Buscar sucursal..." />
    </div>
    <button type="submit"
        class="inline-flex items-center justify-center shrink-0 text-white bg-brand-black-coffe hover:bg-brand-brown-dark focus:ring-4 focus:ring-brand-brown/30 shadow rounded-lg w-10 h-10 focus:outline-none">
        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
        </svg>
        <span class="sr-only">Buscar</span>
    </button>
</form>


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
                                <li class="text-gray-400-italic">Sin productos</li>
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
