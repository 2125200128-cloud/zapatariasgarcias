@extends('/plantilla/base')

@section('dinamico')

<h1 class="text-2xl font-semibold text-gray-800 mb-4">Cancelar trayecto</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <p class="text-gray-700 mb-4">¿Seguro que quieres cancelar el trayecto <strong>#{{ $trayecto->id }}</strong>? El chofer y el carro quedarán libres para un trayecto nuevo.</p>

    <table class="w-full text-sm mb-4 border border-gray-200 rounded-lg overflow-hidden">
        <tr class="border-b border-gray-200"><th class="text-left px-3 py-2 bg-gray-50 w-1/3">ID</th><td class="px-3 py-2">{{ $trayecto->id }}</td></tr>
        <tr class="border-b border-gray-200"><th class="text-left px-3 py-2 bg-gray-50">Descripción</th><td class="px-3 py-2">{{ $trayecto->descripcion_ruta }}</td></tr>
        <tr><th class="text-left px-3 py-2 bg-gray-50">Estatus actual</th><td class="px-3 py-2">{{ $trayecto->estatus }}</td></tr>
    </table>

    <div class="flex gap-3">
        <form action="{{ url('/trayecto/eliminar/' . $trayecto->id) }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Sí, cancelar</button>
        </form>
        <a href="{{ url('/trayecto/lista') }}" class="px-4 py-2 rounded-lg text-sm border border-gray-300 hover:bg-gray-50">Volver</a>
    </div>
</div>

@endsection
