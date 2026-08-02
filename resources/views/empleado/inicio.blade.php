@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Empleados</h1>
    <a href="{{ url('/empleado/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        Nuevo empleado
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Imagen</th>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Nombre</th>
                <th class="px-4 py-3">Apellido paterno</th>
                <th class="px-4 py-3">Apellido materno</th>
                <th class="px-4 py-3">Teléfono</th>
                <th class="px-4 py-3">Correo</th>
                <th class="px-4 py-3">Usuario</th>
                <th class="px-4 py-3">Rol</th>
                <th class="px-4 py-3">Municipio</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($empleados ?? [] as $empleado)
                <tr>
                    <td class="px-4 py-3">
                        <div class="w-10 h-10 rounded bg-gray-100 overflow-hidden flex items-center justify-center">
                            <img src="{{ $empleado->imagen }}" class="w-full h-full object-cover" onerror="this.remove()">
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ $empleado->id }}</td>
                    <td class="px-4 py-3">{{ $empleado->nombre }}</td>
                    <td class="px-4 py-3">{{ $empleado->apellido_paterno }}</td>
                    <td class="px-4 py-3">{{ $empleado->apellido_materno }}</td>
                    <td class="px-4 py-3">{{ $empleado->telefono }}</td>
                    <td class="px-4 py-3">{{ $empleado->correo }}</td>
                    <td class="px-4 py-3">{{ $empleado->usuario }}</td>
                    <td class="px-4 py-3">{{ $empleado->rol }}</td>
                    <td class="px-4 py-3">{{ $empleado->municipio }}</td>
                    <td class="px-4 py-3">{{ $empleado->estatus }}</td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        <a href="{{ url('/empleado/editar/' . $empleado->id) }}" class="text-blue-600 hover:underline">Editar</a>
                        <a href="{{ url('/empleado/mostrar/' . $empleado->id) }}" class="text-red-600 hover:underline">Eliminar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="px-4 py-6 text-center text-gray-500">No hay empleados registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
