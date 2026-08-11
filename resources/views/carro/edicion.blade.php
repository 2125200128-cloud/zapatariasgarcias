@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-3xl rounded-lg bg-white p-6 shadow">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Editar carro</h1>
            <p class="text-sm text-gray-500">Actualiza la información del carro en la API.</p>
        </div>
        <a href="{{ url('/carro') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Volver</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ url('/carro/actualizar/' . data_get($carro, 'id', '')) }}" method="POST" enctype="multipart/form-data" class="grid gap-4 sm:grid-cols-2">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Placas</label>
            <input type="text" name="placas" value="{{ old('placas', data_get($carro, 'placas', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
            <input type="text" name="marca" value="{{ old('marca', data_get($carro, 'marca', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
            <input type="text" name="color" value="{{ old('color', data_get($carro, 'color', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Capacidad</label>
            <input type="number" step="1" name="capacidad" value="{{ old('capacidad', data_get($carro, 'capacidad', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dimensiones</label>
            <input type="text" name="dimenciones" value="{{ old('dimenciones', data_get($carro, 'dimenciones', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="Disponible" {{ old('estatus', data_get($carro, 'estatus', '')) === 'Disponible' ? 'selected' : '' }}>Disponible</option>
                <option value="Ocupado" {{ old('estatus', data_get($carro, 'estatus', '')) === 'Ocupado' ? 'selected' : '' }}>Ocupado</option>
            </select>
        </div>

        <div class="sm:col-span-2 border-t border-gray-100 pt-4 mt-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">Imagen del carro</label>
            
            @php
                $imagenActual = data_get($carro, 'imagen');
                // Si viene de Cloudinary ya es URL completa; si es ruta local usa asset(), o un placeholder por defecto
                $urlImagen = (!empty($imagenActual) && str_starts_with($imagenActual, 'http')) 
                    ? $imagenActual 
                    : ($imagenActual && $imagenActual !== 'sin-imagen.jpg' ? asset($imagenActual) : asset('images/sin-imagen.jpg'));
            @endphp

            <div class="flex items-center gap-4 mb-3">
                {{-- Contenedor con la foto actual --}}
                <div class="relative w-28 h-28 rounded-lg overflow-hidden border border-gray-200 bg-gray-100 flex-shrink-0 shadow-sm">
                    <img id="img-preview" 
                         src="{{ $urlImagen }}" 
                         alt="Vista previa" 
                         class="w-full h-full object-cover">
                </div>
                
                <div class="text-xs text-gray-500 space-y-1">
                    <p class="font-semibold text-gray-700">Imagen cargada actualmente</p>
                    <p>Si deseas cambiar la foto, selecciona un nuevo archivo desde tu equipo.</p>
                    <p class="text-gray-400">Si dejas este campo vacío, la imagen actual permanecerá igual.</p>
                </div>
            </div>

            {{-- Input para subir la nueva foto --}}
            <label class="block text-xs font-medium text-gray-600 mb-1">Subir nueva imagen (opcional)</label>
            <input type="file" 
                   name="imagen" 
                   id="input-imagen"
                   accept="image/*"
                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors">
        </div>

        <div class="sm:col-span-2 flex justify-end gap-3">
            <a href="{{ url('/carro') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar cambios</button>
        </div>
    </form>
</div>

<script>
    document.getElementById('input-imagen')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('img-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>

@endsection
