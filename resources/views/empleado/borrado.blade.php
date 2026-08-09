@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-lg rounded-lg bg-white p-6 shadow">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Confirmar eliminación</h1>
    <p class="text-sm text-gray-600 mb-4">
        ¿Seguro que quieres eliminar al empleado 
        <strong>{{ data_get($empleado, 'nombre', '—') }} {{ data_get($empleado, 'apellido_paterno', '') }}</strong>?
    </p>

    <table class="w-full text-sm mb-4 border border-gray-200 rounded-lg overflow-hidden">
        <tr class="border-b border-gray-200">
            <th class="text-left px-3 py-2 bg-gray-50 w-1/3">ID</th>
            <td class="px-3 py-2">{{ data_get($empleado, 'id', '—') }}</td>
        </tr>
        <tr class="border-b border-gray-200">
            <th class="text-left px-3 py-2 bg-gray-50">Nombre</th>
            <td class="px-3 py-2">
                {{ data_get($empleado, 'nombre', '—') }} 
                {{ data_get($empleado, 'apellido_paterno', '') }} 
                {{ data_get($empleado, 'apellido_materno', '') }}
            </td>
        </tr>
        <tr class="border-b border-gray-200">
            <th class="text-left px-3 py-2 bg-gray-50">Correo</th>
            <td class="px-3 py-2">{{ data_get($empleado, 'correo', '—') }}</td>
        </tr>
        <tr class="border-b border-gray-200">
            <th class="text-left px-3 py-2 bg-gray-50">Rol</th>
            <td class="px-3 py-2">{{ data_get($empleado, 'rol', '—') }}</td>
        </tr>
        <tr>
            <th class="text-left px-3 py-2 bg-gray-50">Estatus</th>
            <td class="px-3 py-2">{{ data_get($empleado, 'estatus', '—') }}</td>
        </tr>
    </table>

    <div class="flex gap-3">
        <form action="{{ url('/empleado/eliminar/' . data_get($empleado, 'id', '')) }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                Sí, eliminar
            </button>
        </form>
        <a href="{{ url('/empleado') }}" class="px-4 py-2 rounded-lg text-sm border border-gray-300 hover:bg-gray-50">
            Cancelar
        </a>
    </div>
</div>

@endsection
