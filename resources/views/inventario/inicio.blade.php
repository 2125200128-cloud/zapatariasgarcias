@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">{{ $puedeGestionar ? 'Inventario' : 'Mi inventario' }}</h1>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($inventarios ?? []) }} registros</span>
        @if ($puedeGestionar)
            <a href="{{ url('/inventario/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                Nuevo registro
            </a>
        @endif
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
                @if ($puedeGestionar)
                    <th class="px-4 py-3">Sucursal</th>
                @endif
                <th class="px-4 py-3">Producto</th>
                <th class="px-4 py-3">Marca</th>
                <th class="px-4 py-3">Talla</th>
                <th class="px-4 py-3">Stock</th>
                <th class="px-4 py-3">Estatus</th>
                @if ($puedeGestionar)
                    <th class="px-4 py-3">Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($inventarios ?? [] as $inventario)
                <tr>
                    <td class="px-4 py-3">{{ data_get($inventario, 'id', '—') }}</td>
                    @if ($puedeGestionar)
                        <td class="px-4 py-3">{{ data_get($inventario, 'sucursal.nombre', '—') }}</td>
                    @endif
                    <td class="px-4 py-3">{{ data_get($inventario, 'producto.nombre', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($inventario, 'producto.marca.nombre', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($inventario, 'producto.talla', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($inventario, 'stock', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($inventario, 'estatus', '—') }}</td>
                    @if ($puedeGestionar)
                        <td class="px-4 py-3 whitespace-nowrap space-x-2">
                            <a href="{{ url('/inventario/editar/' . data_get($inventario, 'id', '')) }}" class="text-blue-600 hover:underline">Editar</a>
                            <a href="{{ url('/inventario/mostrar/' . data_get($inventario, 'id', '')) }}" class="text-red-600 hover:underline">Eliminar</a>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $puedeGestionar ? 8 : 6 }}" class="px-4 py-6 text-center text-gray-500">
                        No hay registros de inventario.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection