@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Proveedores</h1>
    <a href="{{ url('/proveedor/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        Nuevo proveedor
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Imagen</th>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Nombre</th>
                <th class="px-4 py-3">Contacto</th>
                <th class="px-4 py-3">Correo</th>
                <th class="px-4 py-3">Municipio</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($proveedores ?? [] as $proveedor)
                <tr>
                    <td class="px-4 py-3">
                        <div class="w-10 h-10 rounded bg-gray-100 overflow-hidden flex items-center justify-center">
                            <img src="{{ $proveedor->imagen }}" class="w-full h-full object-cover" onerror="this.remove()">
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ $proveedor->id }}</td>
                    <td class="px-4 py-3">{{ $proveedor->nombre }}</td>
                    <td class="px-4 py-3">{{ $proveedor->contacto }}</td>
                    <td class="px-4 py-3">{{ $proveedor->correo }}</td>
                    <td class="px-4 py-3">{{ $proveedor->municipio }}</td>
                    <td class="px-4 py-3">{{ $proveedor->estatus }}</td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        <a href="{{ url('/proveedor/editar/' . $proveedor->id) }}" class="text-blue-600 hover:underline">Editar</a>
                        <a href="{{ url('/proveedor/mostrar/' . $proveedor->id) }}" class="text-red-600 hover:underline">Eliminar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">No hay proveedores registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
