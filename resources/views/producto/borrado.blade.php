@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-2xl rounded-lg bg-white p-6 shadow">
    <h1 class="text-2xl font-semibold text-gray-800">Confirmar eliminación</h1>
    <p class="mt-3 text-sm text-gray-600">
        ¿Deseas continuar con la eliminación del producto <strong>{{ data_get($producto, 'nombre', 'este producto') }}</strong>?
        La API marcará el registro como agotado si tiene dependencias.
    </p>

    <form action="{{ url('/producto/eliminar/' . data_get($producto, 'id', '')) }}" method="POST" class="mt-6 flex gap-3">
        @csrf
        <a href="{{ url('/producto') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
        <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Confirmar</button>
    </form>
</div>

@endsection
