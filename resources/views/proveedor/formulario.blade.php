@extends('/plantilla/base')

@section('dinamico')

<h1 class="text-2xl font-semibold text-gray-800 mb-4">Nuevo proveedor</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/proveedor/guardar') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @csrf

        <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="contacto" class="block text-sm font-medium text-gray-700 mb-1">Contacto (teléfono a 10 dígitos)</label>
            <input type="text" name="contacto" id="contacto" value="{{ old('contacto') }}" maxlength="10" inputmode="numeric" pattern="[0-9]{10}" placeholder="3312223344" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="correo" class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
            <input type="email" name="correo" id="correo" value="{{ old('correo') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="estatus" class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" id="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Activo" @selected(old('estatus', 'Activo') === 'Activo')>Activo</option>
                <option value="Inactivo" @selected(old('estatus') === 'Inactivo')>Inactivo</option>
            </select>
        </div>

        <div>
            <label for="calle" class="block text-sm font-medium text-gray-700 mb-1">Calle</label>
            <input type="text" name="calle" id="calle" value="{{ old('calle') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="numero" class="block text-sm font-medium text-gray-700 mb-1">Número</label>
            <input type="number" name="numero" id="numero" value="{{ old('numero') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="municipio" class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
            <input type="text" name="municipio" id="municipio" value="{{ old('municipio') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="codigo_postal" class="block text-sm font-medium text-gray-700 mb-1">Código postal</label>
            <input type="text" name="codigo_postal" id="codigo_postal" maxlength="5" value="{{ old('codigo_postal') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="sm:col-span-2">
            <label for="imagen" class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
            <input type="file" name="imagen" id="imagen" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="sm:col-span-2">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar</button>
        </div>
    </form>
</div>

@endsection
