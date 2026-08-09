@extends('/plantilla/base')

@section('dinamico')

<div class="flex items-center justify-between mb-4">
    <h1 class="text-2xl font-semibold text-gray-800">Lista de trayectos</h1>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ count($trayectos ?? []) }} registros</span>
        <a href="{{ url('/trayecto/flota') }}" class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 text-sm">
            Ver mapa de flota
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
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Chofer</th>
                <th class="px-4 py-3">Carro</th>
                <th class="px-4 py-3">Pedido</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($trayectos ?? [] as $trayecto)
                <tr>
                    <td class="px-4 py-3">{{ data_get($trayecto, 'id', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($trayecto, 'chofer.nombre', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($trayecto, 'carro.placas', '—') }}</td>
                    <td class="px-4 py-3">#{{ data_get($trayecto, 'pedido_id', '—') }}</td>
                    <td class="px-4 py-3">{{ data_get($trayecto, 'estatus', '—') }}</td>
                    <td class="px-4 py-3 whitespace-nowrap space-x-2">
                        <a href="{{ url('/trayecto/editar/' . data_get($trayecto, 'id', '')) }}" class="text-blue-600 hover:underline">Editar</a>
                        <a href="{{ url('/trayecto/mostrar/' . data_get($trayecto, 'id', '')) }}" class="text-red-600 hover:underline">Eliminar</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">No hay trayectos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<p class="text-xs text-gray-500 mt-2">
    La información se obtiene desde la API externa y se muestra en modo lectura.
</p>

@endsection
