@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-lg rounded-lg bg-white p-6 shadow">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Confirmar eliminación</h1>
    <p class="text-sm text-gray-600 mb-4">
        ¿Seguro que quieres eliminar el carro <strong>{{ data_get($carro, 'placas', '—') }}</strong>?
    </p>

    <table class="w-full text-sm mb-4 border border-gray-200 rounded-lg overflow-hidden">
        <tr class="border-b border-gray-200">
            <th class="text-left px-3 py-2 bg-gray-50 w-1/3">ID</th>
            <td class="px-3 py-2">{{ data_get($carro, 'id', '—') }}</td>
        </tr>
        <tr class="border-b border-gray-200">
            <th class="text-left px-3 py-2 bg-gray-50">Placas</th>
            <td class="px-3 py-2">{{ data_get($carro, 'placas', '—') }}</td>
        </tr>
        <tr class="border-b border-gray-200">
            <th class="text-left px-3 py-2 bg-gray-50">Marca</th>
            <td class="px-3 py-2">{{ data_get($carro, 'marca', '—') }}</td>
        </tr>
        <tr>
            <th class="text-left px-3 py-2 bg-gray-50">Estatus</th>
            <td class="px-3 py-2">{{ data_get($carro, 'estatus', '—') }}</td>
        </tr>
    </table>

    <div class="flex gap-3">
        <form action="{{ url('/carro/eliminar/' . data_get($carro, 'id', '')) }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                Sí, eliminar
            </button>
        </form>
        <a href="{{ url('/carro') }}" class="px-4 py-2 rounded-lg text-sm border border-gray-300 hover:bg-gray-50">
            Cancelar
        </a>
    </div>
</div>

@endsection
