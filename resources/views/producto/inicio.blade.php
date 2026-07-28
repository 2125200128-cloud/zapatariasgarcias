@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Productos</h1>
    <a href="{{ url('/producto/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        Nuevo producto
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Nombre</th>
                <th class="px-4 py-3">Marca</th>
                <th class="px-4 py-3">Proveedor</th>
                <th class="px-4 py-3">Precio</th>
                <th class="px-4 py-3">Talla</th>
                <th class="px-4 py-3">Categoría</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($productos ?? [] as $producto)
                <tr>
                    <td class="px-4 py-3">{{ $producto->id }}</td>
                    <td class="px-4 py-3">{{ $producto->nombre }}</td>
                    <td class="px-4 py-3">{{ $producto->marca->nombre ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $producto->proveedor->nombre ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $producto->precio }}</td>
                    <td class="px-4 py-3">{{ $producto->talla }}</td>
                    <td class="px-4 py-3">{{ $producto->categoria }}</td>
                    <td class="px-4 py-3">{{ $producto->estatus }}</td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        <a href="{{ url('/producto/editar/' . $producto->id) }}" class="text-blue-600 hover:underline">Editar</a>
                        <a href="{{ url('/producto/mostrar/' . $producto->id) }}" class="text-red-600 hover:underline">Eliminar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-4 py-6 text-center text-gray-500">No hay productos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
