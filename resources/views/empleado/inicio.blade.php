@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Empleados</h1>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($empleados ?? []) }} empleados registrados</span>
        <a href="{{ url('/empleado/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Nuevo empleado
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
                            <img src="{{ data_get($empleado, 'imagen', '') }}" class="w-full h-full object-cover" onerror="this.remove()">
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ data_get($empleado, 'id', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($empleado, 'nombre', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($empleado, 'apellido_paterno', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($empleado, 'apellido_materno', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($empleado, 'telefono', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($empleado, 'correo', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($empleado, 'usuario', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($empleado, 'rol', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($empleado, 'municipio', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($empleado, 'estatus', '—') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        <a href="{{ url('/empleado/editar/' . data_get($empleado, 'id', '')) }}" class="text-blue-600 hover:underline">Editar</a>
                        <a href="{{ url('/empleado/mostrar/' . data_get($empleado, 'id', '')) }}" class="text-red-600 hover:underline">Eliminar</a>
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
