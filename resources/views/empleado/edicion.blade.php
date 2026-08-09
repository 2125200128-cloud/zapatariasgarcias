@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-3xl rounded-lg bg-white p-6 shadow">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Editar empleado</h1>
            <p class="text-sm text-gray-500">Actualiza la información del empleado en la API.</p>
        </div>
        <a href="{{ url('/empleado') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Volver</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ url('/empleado/actualizar/' . data_get($empleado, 'id', '')) }}" method="POST" enctype="multipart/form-data" class="grid gap-4 sm:grid-cols-2">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre', data_get($empleado, 'nombre', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido paterno</label>
            <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno', data_get($empleado, 'apellido_paterno', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido materno</label>
            <input type="text" name="apellido_materno" value="{{ old('apellido_materno', data_get($empleado, 'apellido_materno', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
            <input type="text" name="telefono" maxlength="10" value="{{ old('telefono', data_get($empleado, 'telefono', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
            <input type="email" name="correo" value="{{ old('correo', data_get($empleado, 'correo', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
            <input type="text" name="usuario" value="{{ old('usuario', data_get($empleado, 'usuario', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña <span class="text-gray-400 font-normal">(dejar vacío para no cambiarla)</span></label>
            <input type="password" name="contrasena" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
            <select name="rol" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="Empleado" {{ old('rol', data_get($empleado, 'rol', '')) === 'Empleado' ? 'selected' : '' }}>Empleado</option>
                <option value="Administrador" {{ old('rol', data_get($empleado, 'rol', '')) === 'Administrador' ? 'selected' : '' }}>Administrador</option>
                <option value="Encargado" {{ old('rol', data_get($empleado, 'rol', '')) === 'Encargado' ? 'selected' : '' }}>Encargado</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="Activo" {{ old('estatus', data_get($empleado, 'estatus', '')) === 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Inactivo" {{ old('estatus', data_get($empleado, 'estatus', '')) === 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Calle</label>
            <input type="text" name="calle" value="{{ old('calle', data_get($empleado, 'calle', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
            <input type="number" name="numero" value="{{ old('numero', data_get($empleado, 'numero', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
            <input type="text" name="municipio" value="{{ old('municipio', data_get($empleado, 'municipio', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Código postal</label>
            <input type="text" name="codigo_postal" maxlength="5" value="{{ old('codigo_postal', data_get($empleado, 'codigo_postal', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
            <input type="file" name="imagen" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="sm:col-span-2 flex justify-end gap-3">
            <a href="{{ url('/empleado') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar cambios</button>
        </div>
    </form>
</div>

@endsection
