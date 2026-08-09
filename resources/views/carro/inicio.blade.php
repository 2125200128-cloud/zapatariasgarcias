@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Carros</h1>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($carros ?? []) }} carros registrados</span>
        <a href="{{ url('/carro/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Nuevo carro
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
                            <img src="{{ data_get($carro, 'imagen', '') }}" class="w-full h-full object-cover" onerror="this.remove()">
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ data_get($carro, 'id', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($carro, 'placas', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($carro, 'marca', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($carro, 'color', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($carro, 'capacidad', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($carro, 'estatus', '—') }}</td>
                    <td class="px-4 py-3">
                        @php $activo = data_get($carro, 'trayectos.0', null); @endphp
                        @if ($activo)
                            <span class="text-amber-700 bg-amber-50 border border-amber-200 px-2 py-1 rounded-full text-xs">
                                Trayecto #{{ data_get($activo, 'id', '') }} — 
                                {{ data_get($activo, 'chofer.nombre', 'sin chofer') }} {{ data_get($activo, 'chofer.apellido', '') }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs">Libre</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        <a href="{{ url('/carro/editar/' . data_get($carro, 'id', '')) }}" class="text-blue-600 hover:underline">Editar</a>
                        <a href="{{ url('/carro/mostrar/' . data_get($carro, 'id', '')) }}" class="text-red-600 hover:underline">Eliminar</a>
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
