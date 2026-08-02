@extends('/plantilla/base')

@section('dinamico')

<h1 class="text-2xl font-semibold text-gray-800 mb-4">Editar empleado</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ url('/empleado/actualizar/' . $empleado->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @csrf

        <div>
            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{ $empleado->nombre }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="apellido_paterno" class="block text-sm font-medium text-gray-700 mb-1">Apellido paterno</label>
            <input type="text" name="apellido_paterno" id="apellido_paterno" value="{{ $empleado->apellido_paterno }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="apellido_materno" class="block text-sm font-medium text-gray-700 mb-1">Apellido materno</label>
            <input type="text" name="apellido_materno" id="apellido_materno" value="{{ $empleado->apellido_materno }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
            <input type="text" name="telefono" id="telefono" maxlength="10" value="{{ $empleado->telefono }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="correo" class="block text-sm font-medium text-gray-700 mb-1">Correo</label>
            <input type="email" name="correo" id="correo" value="{{ $empleado->correo }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="usuario" class="block text-sm font-medium text-gray-700 mb-1">Usuario</label>
            <input type="text" name="usuario" id="usuario" value="{{ $empleado->usuario }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="contrasena" class="block text-sm font-medium text-gray-700 mb-1">Contraseña <span class="text-gray-400 font-normal">(dejar vacío para no cambiarla)</span></label>
            <input type="password" name="contrasena" id="contrasena" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="rol" class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
            <select name="rol" id="rol" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Empleado" @selected($empleado->rol === 'Empleado')>Empleado</option>
                <option value="Administrador" @selected($empleado->rol === 'Administrador')>Administrador</option>
                <option value="Encargado" @selected($empleado->rol === 'Encargado')>Encargado</option>
            </select>
        </div>

        <div>
            <label for="estatus" class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" id="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Activo" @selected($empleado->estatus === 'Activo')>Activo</option>
                <option value="Inactivo" @selected($empleado->estatus === 'Inactivo')>Inactivo</option>
            </select>
        </div>

        <div>
            <label for="calle" class="block text-sm font-medium text-gray-700 mb-1">Calle</label>
            <input type="text" name="calle" id="calle" value="{{ $empleado->calle }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="numero" class="block text-sm font-medium text-gray-700 mb-1">Número</label>
            <input type="number" name="numero" id="numero" value="{{ $empleado->numero }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="municipio" class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
            <input type="text" name="municipio" id="municipio" value="{{ $empleado->municipio }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="codigo_postal" class="block text-sm font-medium text-gray-700 mb-1">Código postal</label>
            <input type="text" name="codigo_postal" id="codigo_postal" maxlength="5" value="{{ $empleado->codigo_postal }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="sm:col-span-2">
            <label for="imagen" class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
            <input type="file" name="imagen" id="imagen" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="sm:col-span-2">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar cambios</button>
        </div>
    </form>
</div>

@endsection
