@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Carros</h1>
    <a href="{{ url('/carro/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
        Nuevo carro
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Imagen</th>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Placas</th>
                <th class="px-4 py-3">Marca</th>
                <th class="px-4 py-3">Color</th>
                <th class="px-4 py-3">Capacidad</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3">Trayecto activo</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($carros ?? [] as $carro)
                <tr>
                    <td class="px-4 py-3">
                        <div class="w-10 h-10 rounded bg-gray-100 overflow-hidden flex items-center justify-center">
                            <img src="{{ $carro->imagen }}" class="w-full h-full object-cover" onerror="this.remove()">
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ $carro->id }}</td>
                    <td class="px-4 py-3">{{ $carro->placas }}</td>
                    <td class="px-4 py-3">{{ $carro->marca }}</td>
                    <td class="px-4 py-3">{{ $carro->color }}</td>
                    <td class="px-4 py-3">{{ $carro->capacidad }}</td>
                    <td class="px-4 py-3">{{ $carro->estatus }}</td>
                    <td class="px-4 py-3">
                        @php $activo = $carro->trayectos->first(); @endphp
                        @if ($activo)
                            <span class="text-amber-700 bg-amber-50 border border-amber-200 px-2 py-1 rounded-full text-xs">
                                Trayecto #{{ $activo->id }} — {{ $activo->chofer ? $activo->chofer->nombre . ' ' . $activo->chofer->apellido : 'sin chofer' }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs">Libre</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        <a href="{{ url('/carro/editar/' . $carro->id) }}" class="text-blue-600 hover:underline">Editar</a>
                        <a href="{{ url('/carro/mostrar/' . $carro->id) }}" class="text-red-600 hover:underline">Eliminar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="px-4 py-6 text-center text-gray-500">No hay carros registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
