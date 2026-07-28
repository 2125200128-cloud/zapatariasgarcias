@extends('/plantilla/base')

@section('dinamico')

<h1 class="text-2xl font-semibold text-gray-800 mb-4">Eliminar marca</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <p class="text-gray-700 mb-4">¿Seguro que quieres eliminar la marca <strong>{{ $marca->nombre }}</strong>?</p>

    <table class="w-full text-sm mb-4 border border-gray-200 rounded-lg overflow-hidden">
        <tr class="border-b border-gray-200"><th class="text-left px-3 py-2 bg-gray-50 w-1/3">ID</th><td class="px-3 py-2">{{ $marca->id }}</td></tr>
        <tr class="border-b border-gray-200"><th class="text-left px-3 py-2 bg-gray-50">Nombre</th><td class="px-3 py-2">{{ $marca->nombre }}</td></tr>
        <tr><th class="text-left px-3 py-2 bg-gray-50">Proveedor</th><td class="px-3 py-2">{{ $marca->proveedor->nombre ?? '—' }}</td></tr>
    </table>

    <div class="flex gap-3">
        <form action="{{ url('/marca/eliminar/' . $marca->id) }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Sí, eliminar</button>
        </form>
        <a href="{{ url('/marca') }}" class="px-4 py-2 rounded-lg text-sm border border-gray-300 hover:bg-gray-50">Cancelar</a>
    </div>
</div>

@endsection
