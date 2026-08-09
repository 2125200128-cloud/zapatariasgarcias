@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-3xl rounded-lg bg-white p-6 shadow">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Nuevo chofer</h1>
            <p class="text-sm text-gray-500">Registra un chofer en la API de ZAPATERIA_API.</p>
        </div>
        <a href="{{ url('/chofer') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Volver</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ url('/chofer/guardar') }}" method="POST" enctype="multipart/form-data" class="grid gap-4 sm:grid-cols-2">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
            <input type="text" name="apellido" value="{{ old('apellido') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contacto</label>
            <input type="text" name="contacto" maxlength="10" value="{{ old('contacto') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="Activo" {{ old('estatus') === 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Inactivo" {{ old('estatus') === 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
            <input type="file" name="imagen" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="sm:col-span-2 flex justify-end gap-3">
            <a href="{{ url('/chofer') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar</button>
        </div>
    </form>
</div>

@endsection
