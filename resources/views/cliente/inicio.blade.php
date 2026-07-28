@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Clientes</h1>
    <a href="{{ url('/cliente/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        Nuevo cliente
    </a>
</div>

@if (session('success'))
    <p class="text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-2 mb-4 text-sm">{{ session('success') }}</p>
@endif
@if (session('error'))
    <p class="text-red-700 bg-red-50 border border-red-200 rounded-lg px-4 py-2 mb-4 text-sm">{{ session('error') }}</p>
@endif

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Nombre</th>
                <th class="px-4 py-3">Apellido paterno</th>
                <th class="px-4 py-3">Apellido materno</th>
                <th class="px-4 py-3">Teléfono</th>
                <th class="px-4 py-3">Correo</th>
                <th class="px-4 py-3">Usuario</th>
                <th class="px-4 py-3">Municipio</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($clientes ?? [] as $cliente)
                <tr>
                    <td class="px-4 py-3">{{ $cliente->id }}</td>
                    <td class="px-4 py-3">{{ $cliente->nombre }}</td>
                    <td class="px-4 py-3">{{ $cliente->apellido_paterno }}</td>
                    <td class="px-4 py-3">{{ $cliente->apellido_materno }}</td>
                    <td class="px-4 py-3">{{ $cliente->telefono }}</td>
                    <td class="px-4 py-3">{{ $cliente->correo }}</td>
                    <td class="px-4 py-3">{{ $cliente->usuario }}</td>
                    <td class="px-4 py-3">{{ $cliente->municipio }}</td>
                    <td class="px-4 py-3">{{ $cliente->estatus }}</td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        <a href="{{ url('/cliente/editar/' . $cliente->id) }}" class="text-blue-600 hover:underline">Editar</a>
                        <a href="{{ url('/cliente/mostrar/' . $cliente->id) }}" class="text-red-600 hover:underline">Eliminar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="px-4 py-6 text-center text-gray-500">No hay clientes registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
