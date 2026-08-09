@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Productos</h1>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($grupos ?? []) }} grupos visibles</span>
        <a href="{{ url('/producto/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Nuevo producto
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
                <th class="px-4 py-3">Nombre</th>
                <th class="px-4 py-3">Marca</th>
                <th class="px-4 py-3">Proveedor</th>
                <th class="px-4 py-3">Precio</th>
                <th class="px-4 py-3">Tallas</th>
                <th class="px-4 py-3">Categoría</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($grupos ?? [] as $grupo)
                @php
                    $primero = $grupo->first();
                @endphp
                <tr>
                    <td class="px-4 py-3">{{ data_get($primero, 'nombre', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($primero, 'marca.nombre', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($primero, 'proveedor.nombre', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($primero, 'precio', '—') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @foreach ($grupo as $variante)
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-700">
                                    {{ data_get($variante, 'talla', '—') }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ data_get($primero, 'categoria', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($primero, 'estatus', '—') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <div class="flex gap-3">
                            <a href="{{ url('/producto/editar/' . data_get($primero, 'id', '')) }}" class="text-blue-600 hover:underline">Editar</a>
                            <a href="{{ url('/producto/mostrar/' . data_get($primero, 'id', '')) }}" class="text-red-600 hover:underline">Eliminar</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">No hay productos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


@endsection
