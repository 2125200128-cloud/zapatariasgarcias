@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Pedidos</h1>
    <a href="{{ url('/pedido/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        Nuevo pedido
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Empleado</th>
                <th class="px-4 py-3">Fecha</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($pedidos ?? [] as $pedido)
                <tr>
                    <td class="px-4 py-3">{{ $pedido->id }}</td>
                    <td class="px-4 py-3">{{ $pedido->empleado->nombre ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $pedido->fecha }}</td>
                    <td class="px-4 py-3">{{ $pedido->estatus }}</td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        <a href="{{ url('/pedido/editar/' . $pedido->id) }}" class="text-blue-600 hover:underline">Editar</a>
                        <a href="{{ url('/pedido/mostrar/' . $pedido->id) }}" class="text-red-600 hover:underline">Eliminar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">No hay pedidos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
