@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Productos</h1>
    <a href="{{ url('/producto/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        Nuevo producto
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
                <th class="px-4 py-3">Imagen</th>
                <th class="px-4 py-3">Nombre</th>
                <th class="px-4 py-3">Marca</th>
                <th class="px-4 py-3">Proveedor</th>
                <th class="px-4 py-3">Precio</th>
                <th class="px-4 py-3">Tallas</th>
                <th class="px-4 py-3">Categoría</th>
                <th class="px-4 py-3">Estatus</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($grupos ?? [] as $grupo)
                @php
                    $primero = $grupo->first();
                @endphp
                <tr>
                    <td class="px-4 py-3">
                        <div class="w-10 h-10 rounded bg-gray-100 overflow-hidden flex items-center justify-center">
                            <img src="{{ $primero->imagen1 }}" class="w-full h-full object-cover" onerror="this.remove()">
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ $primero->nombre }}</td>
                    <td class="px-4 py-3">{{ $primero->marca->nombre ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $primero->proveedor->nombre ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $primero->precio }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            @foreach ($grupo as $variante)
                                <span class="inline-flex items-center gap-1 bg-gray-100 rounded-full pl-2 pr-1 py-0.5 text-xs">
                                    <a href="{{ url('/producto/editar/' . $variante->id) }}" class="hover:text-blue-600" title="Editar talla {{ $variante->talla }}">
                                        {{ $variante->talla }}
                                    </a>
                                    <a href="{{ url('/producto/mostrar/' . $variante->id) }}" class="text-gray-400 hover:text-red-600 leading-none" title="Eliminar talla {{ $variante->talla }}">
                                        &times;
                                    </a>
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ $primero->categoria }}</td>
                    <td class="px-4 py-3">{{ $primero->estatus }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">No hay productos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<p class="text-xs text-gray-500 mt-2">
    Cada número de talla es un enlace para editar esa talla; la "×" junto a ella la elimina.
</p>

@endsection
