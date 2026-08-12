@extends('/plantilla/base')

@section('dinamico')

    <div class="max-w-3xl rounded-lg bg-white p-6 shadow">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Editar sucursal</h1>
            </div>
            <a href="{{ url('/sucursal') }}"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Volver</a>
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

        <form action="{{ url('/sucursal/actualizar/' . data_get($sucursal, 'id', '')) }}" method="POST"
            enctype="multipart/form-data" class="grid gap-4 sm:grid-cols-2">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="nombre" value="{{ old('nombre', data_get($sucursal, 'nombre', '')) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Empleado encargado</label>
                <select name="empleado_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                    <option value="">-- Selecciona --</option>
                    @foreach ($empleados ?? [] as $empleado)
                        <option value="{{ data_get($empleado, 'id', '') }}" {{ old('empleado_id', data_get($sucursal, 'empleado_id', '')) == data_get($empleado, 'id', '') ? 'selected' : '' }}>
                            {{ data_get($empleado, 'nombre', 'Empleado') }} {{ data_get($empleado, 'apellido_paterno', '') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Calle</label>
                <input type="text" name="calle" value="{{ old('calle', data_get($sucursal, 'calle', '')) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                <input type="number" name="numero" value="{{ old('numero', data_get($sucursal, 'numero', '')) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Municipio</label>
                <input type="text" name="municipio" value="{{ old('municipio', data_get($sucursal, 'municipio', '')) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Código postal</label>
                <input type="text" name="codigo_postal" maxlength="5"
                    value="{{ old('codigo_postal', data_get($sucursal, 'codigo_postal', '')) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contacto</label>
                <input type="text" name="contacto" value="{{ old('contacto', data_get($sucursal, 'contacto', '')) }}"
                    maxlength="10" inputmode="numeric" pattern="[0-9]{10}" placeholder="3312223344"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
                <select name="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="Activo" {{ old('estatus', data_get($sucursal, 'estatus', '')) === 'Activo' ? 'selected' : '' }}>Activo</option>
                    <option value="Inactivo" {{ old('estatus', data_get($sucursal, 'estatus', '')) === 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="sm:col-span-2 border-t border-gray-100 pt-4 mt-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Imagen de la sucursal</label>

                @php
                    $imagenActual = data_get($sucursal, 'imagen');
                    // Si viene de Cloudinary ya es URL completa; si es ruta local usa asset(), o un placeholder por defecto
                    $urlImagen = (!empty($imagenActual) && str_starts_with($imagenActual, 'http'))
                        ? $imagenActual
                        : ($imagenActual && $imagenActual !== 'sin-imagen.jpg' ? asset($imagenActual) : asset('images/sin-imagen.jpg'));
                @endphp

                <div class="flex items-center gap-4 mb-3">
                    {{-- Contenedor con la foto actual --}}
                    <div
                        class="relative w-28 h-28 rounded-lg overflow-hidden border border-gray-200 bg-gray-100 flex-shrink-0 shadow-sm">
                        <img id="img-preview" src="{{ $urlImagen }}" alt="Vista previa" class="w-full h-full object-cover">
                    </div>

                    <div class="text-xs text-gray-500 space-y-1">
                        <p class="font-semibold text-gray-700">Imagen cargada actualmente</p>
                        <p>Si deseas cambiar la foto, selecciona un nuevo archivo desde tu equipo.</p>
                        <p class="text-gray-400">Si dejas este campo vacío, la imagen actual permanecerá igual.</p>
                    </div>
                </div>

                {{-- Input para subir la nueva foto --}}
                <label class="block text-xs font-medium text-gray-600 mb-1">Subir nueva imagen (opcional)</label>
                <input type="file" name="imagen" id="input-imagen" accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors">
            </div>

            <div class="sm:col-span-2 flex justify-end gap-3">
                <a href="{{ url('/sucursal') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar
                    cambios</button>
            </div>
        </form>
    </div>


    <script>
        document.getElementById('input-imagen')?.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('img-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection