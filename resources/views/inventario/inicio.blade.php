@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Inventario</h1>
    <a href="{{ url('/inventario/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        Nuevo registro de inventario
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Sucursal</th>
                <th class="px-4 py-3">Producto</th>
                <th class="px-4 py-3">Stock</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($inventarios ?? [] as $inventario)
                <tr>
                    <td class="px-4 py-3">{{ $inventario->id }}</td>
                    <td class="px-4 py-3">{{ $inventario->sucursal->nombre ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $inventario->producto->nombre ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $inventario->stock }}</td>
                    <td class="px-4 py-3">{{ $inventario->estatus }}</td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        <a href="{{ url('/inventario/editar/' . $inventario->id) }}" class="text-blue-600 hover:underline">Editar</a>
                        <a href="{{ url('/inventario/mostrar/' . $inventario->id) }}" class="text-red-600 hover:underline">Eliminar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay registros de inventario.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
