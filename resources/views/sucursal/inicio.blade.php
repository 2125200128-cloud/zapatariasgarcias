@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Sucursales</h1>
    <a href="{{ url('/sucursal/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        Nueva sucursal
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Nombre</th>
                <th class="px-4 py-3">Municipio</th>
                <th class="px-4 py-3">Contacto</th>
                <th class="px-4 py-3">Encargado</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($sucursales ?? [] as $sucursal)
                <tr>
                    <td class="px-4 py-3">{{ $sucursal->id }}</td>
                    <td class="px-4 py-3">{{ $sucursal->nombre }}</td>
                    <td class="px-4 py-3">{{ $sucursal->municipio }}</td>
                    <td class="px-4 py-3">{{ $sucursal->contacto }}</td>
                    <td class="px-4 py-3">{{ $sucursal->empleado->nombre ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $sucursal->estatus }}</td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        <a href="{{ url('/sucursal/editar/' . $sucursal->id) }}" class="text-blue-600 hover:underline">Editar</a>
                        <a href="{{ url('/sucursal/mostrar/' . $sucursal->id) }}" class="text-red-600 hover:underline">Eliminar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-6 text-center text-gray-500">No hay sucursales registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
