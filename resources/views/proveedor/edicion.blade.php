@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-3xl rounded-lg bg-white p-6 shadow">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Editar proveedor</h1>
            <p class="text-sm text-gray-500">Actualiza la información del proveedor en la API.</p>
        </div>
        <a href="{{ url('/proveedor') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Volver</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/proveedor/actualizar/' . data_get($proveedor, 'id', '')) }}" method="POST" enctype="multipart/form-data" class="grid gap-4 sm:grid-cols-2">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre', data_get($proveedor, 'nombre', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contacto (teléfono a 10 dígitos)</label>
            <input type="text" name="contacto" value="{{ old('contacto', data_get($proveedor, 'contacto', '')) }}" maxlength="10" inputmode="numeric" pattern="[0-9]{10}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
            <input type="email" name="correo" value="{{ old('correo', data_get($proveedor, 'correo', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="Activo" {{ old('estatus', data_get($proveedor, 'estatus', '')) === 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Inactivo" {{ old('estatus', data_get($proveedor, 'estatus', '')) === 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Calle</label>
            <input type="text" name="calle" value="{{ old('calle', data_get($proveedor, 'calle', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
            <input type="number" name="numero" value="{{ old('numero', data_get($proveedor, 'numero', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
            <input type="text" name="municipio" value="{{ old('municipio', data_get($proveedor, 'municipio', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Código postal</label>
            <input type="text" name="codigo_postal" maxlength="5" value="{{ old('codigo_postal', data_get($proveedor, 'codigo_postal', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div class="sm:col-span-2 flex justify-end gap-3">
            <a href="{{ url('/proveedor') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar cambios</button>
        </div>
    </form>
</div>

@endsection
