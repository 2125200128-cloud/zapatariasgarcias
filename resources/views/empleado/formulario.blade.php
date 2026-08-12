@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-3xl rounded-lg bg-white p-6 shadow">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Nuevo empleado</h1>
        </div>
        <a href="{{ url('/empleado') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Volver</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ url('/empleado/guardar') }}" method="POST" enctype="multipart/form-data" class="grid gap-4 sm:grid-cols-2">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido paterno</label>
            <input type="text" name="apellido_paterno" value="{{ old('apellido_paterno') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido materno</label>
            <input type="text" name="apellido_materno" value="{{ old('apellido_materno') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
            <input type="text" name="telefono" maxlength="10" value="{{ old('telefono') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
            <input type="email" name="correo" value="{{ old('correo') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
            <input type="text" name="usuario" value="{{ old('usuario') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
            <input type="password" name="contrasena" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
            <select name="rol" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="Empleado" {{ old('rol') === 'Empleado' ? 'selected' : '' }}>Empleado</option>
                <option value="Administrador" {{ old('rol') === 'Administrador' ? 'selected' : '' }}>Administrador</option>
                <option value="Encargado" {{ old('rol') === 'Encargado' ? 'selected' : '' }}>Encargado</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="Activo" {{ old('estatus') === 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Inactivo" {{ old('estatus') === 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
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

        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
            <input type="file" name="imagen" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="sm:col-span-2 flex justify-end gap-3">
            <a href="{{ url('/empleado') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar</button>
        </div>
    </form>
</div>

@endsection
