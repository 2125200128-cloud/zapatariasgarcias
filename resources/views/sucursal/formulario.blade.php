@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-3xl rounded-lg bg-white p-6 shadow">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Nueva sucursal</h1>
            <p class="text-sm text-gray-500">Registra una nueva sucursal en la API de ZAPATERIA_API.</p>
        </div>
        <a href="{{ url('/sucursal') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Volver</a>
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

    <form action="{{ url('/sucursal/guardar') }}" method="POST" enctype="multipart/form-data" class="grid gap-4 sm:grid-cols-2">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Empleado encargado</label>
            <select name="empleado_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                <option value="">-- Selecciona --</option>
                @foreach ($empleados ?? [] as $empleado)
                    <option value="{{ data_get($empleado, 'id', '') }}" {{ old('empleado_id') == data_get($empleado, 'id', '') ? 'selected' : '' }}>
                        {{ data_get($empleado, 'nombre', 'Empleado') }} {{ data_get($empleado, 'apellido_paterno', 'Empleado') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Calle</label>
            <input type="text" name="calle" value="{{ old('calle') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
            <input type="number" name="numero" value="{{ old('numero') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
            <input type="text" name="municipio" value="{{ old('municipio') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Código postal</label>
            <input type="text" name="codigo_postal" maxlength="5" value="{{ old('codigo_postal') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contacto</label>
            <input type="text" name="contacto" value="{{ old('contacto') }}" maxlength="10" inputmode="numeric" pattern="[0-9]{10}" placeholder="3312223344" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="Activo" {{ old('estatus', 'Activo') === 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Inactivo" {{ old('estatus') === 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
            <input type="file" name="imagen" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="sm:col-span-2 flex justify-end gap-3">
            <a href="{{ url('/sucursal') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar</button>
        </div>
    </form>
</div>

@endsection
