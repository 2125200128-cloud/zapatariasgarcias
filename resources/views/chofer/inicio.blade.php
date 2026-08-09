@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Choferes</h1>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($choferes ?? []) }} choferes registrados</span>
        <a href="{{ url('/chofer/formulario') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Nuevo chofer
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
                <th class="px-4 py-3">Apellido</th>
                <th class="px-4 py-3">Contacto</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3">Trayecto activo</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($choferes ?? [] as $chofer)
                <tr>
                    <td class="px-4 py-3">
                        <div class="w-10 h-10 rounded bg-gray-100 overflow-hidden flex items-center justify-center">
                            <img src="{{ data_get($chofer, 'imagen', '') }}" class="w-full h-full object-cover" onerror="this.remove()">
                        </div>
                    </td>
                    <td class="px-4 py-3">{{ data_get($chofer, 'id', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($chofer, 'nombre', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($chofer, 'apellido', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($chofer, 'contacto', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($chofer, 'estatus', '—') }}</td>
                    <td class="px-4 py-3">
                        @php $activo = data_get($chofer, 'trayectos.0', null); @endphp
                        @if ($activo)
                            <span class="text-amber-700 bg-amber-50 border border-amber-200 px-2 py-1 rounded-full text-xs">
                                Trayecto #{{ data_get($activo, 'id', '') }} — 
                                {{ data_get($activo, 'carro.placas', 'sin carro') }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs">Libre</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        <a href="{{ url('/chofer/editar/' . data_get($chofer, 'id', '')) }}" class="text-blue-600 hover:underline">Editar</a>
                        <a href="{{ url('/chofer/mostrar/' . data_get($chofer, 'id', '')) }}" class="text-red-600 hover:underline">Eliminar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-6 text-center text-gray-500">No hay choferes registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
