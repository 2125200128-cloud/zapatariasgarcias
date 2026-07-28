@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Marcas</h1>
    <a href="{{ url('/marca/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        Nueva marca
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Nombre</th>
                <th class="px-4 py-3">Proveedor</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($marcas ?? [] as $marca)
                <tr>
                    <td class="px-4 py-3">{{ $marca->id }}</td>
                    <td class="px-4 py-3">{{ $marca->nombre }}</td>
                    <td class="px-4 py-3">{{ $marca->proveedor->nombre ?? '—' }}</td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        <a href="{{ url('/marca/editar/' . $marca->id) }}" class="text-blue-600 hover:underline">Editar</a>
                        <a href="{{ url('/marca/mostrar/' . $marca->id) }}" class="text-red-600 hover:underline">Eliminar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">No hay marcas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
