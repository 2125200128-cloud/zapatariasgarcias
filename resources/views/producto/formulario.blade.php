@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-4xl mx-auto bg-cover bg-center p-8 rounded-lg" style="background-image: url('{{ asset('images/fondo-formulario.jpeg') }}')">
    <div class="max-w-3xl rounded-lg bg-white p-6 shadow">

            <div class="max-w-4xl rounded-lg bg-white p-6 shadow">
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-800">Nuevo producto</h1>
                        <p class="text-sm text-gray-500">Crea uno o varios productos para la API de ZAPATERIA_API.</p>
                    </div>
                    <a href="{{ url('/producto') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Volver</a>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ url('/producto/guardar') }}" method="POST" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2">
                    @csrf

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-1 block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="descripcion" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2">{{ old('descripcion') }}</textarea>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Marca</label>
                        <select name="marca_id" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                            <option value="">Selecciona una marca</option>
                            @foreach ($marcas ?? [] as $marca)
                                <option value="{{ data_get($marca, 'id', '') }}" {{ old('marca_id') == data_get($marca, 'id', '') ? 'selected' : '' }}>
                                    {{ data_get($marca, 'nombre', 'Marca') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Proveedor</label>
                        <select name="proveedor_id" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                            <option value="">Selecciona un proveedor</option>
                            @foreach ($proveedores ?? [] as $proveedor)
                                <option value="{{ data_get($proveedor, 'id', '') }}" {{ old('proveedor_id') == data_get($proveedor, 'id', '') ? 'selected' : '' }}>
                                    {{ data_get($proveedor, 'nombre', 'Proveedor') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Precio</label>
                        <input type="number" step="0.01" name="precio" value="{{ old('precio') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2" required>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Modelo</label>
                        <input type="text" name="modelo" value="{{ old('modelo') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Color</label>
                        <input type="text" name="color" value="{{ old('color') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Sexo</label>
                        <input type="text" name="sexo" value="{{ old('sexo') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Categoría</label>
                        <input type="text" name="categoria" value="{{ old('categoria') }}" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-700">Tallas</label>
                        <div class="grid grid-cols-3 gap-2 rounded-lg border border-gray-200 p-3 sm:grid-cols-6">
                            @php $tallas = ['22','22.5','23','23.5','24','24.5','25','25.5','26','26.5','27','27.5','28','28.5','29','29.5','30']; @endphp
                            @foreach ($tallas as $talla)
                                <label class="flex items-center gap-2 text-sm text-gray-600">
                                    <input type="checkbox" name="tallas[]" value="{{ $talla }}" {{ in_array($talla, old('tallas', []), true) ? 'checked' : '' }}>
                                    {{ $talla }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Estatus</label>
                        <select name="estatus" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                            <option value="Activo" {{ old('estatus') === 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Agotado" {{ old('estatus') === 'Agotado' ? 'selected' : '' }}>Agotado</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Imagen 1</label>
                        <input type="file" name="imagen1" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Imagen 2</label>
                        <input type="file" name="imagen2" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700">Imagen 3</label>
                        <input type="file" name="imagen3" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    </div>

                    <div class="md:col-span-2 flex justify-end gap-3">
                        <a href="{{ url('/producto') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
                        <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Guardar</button>
                    </div>
                </form>
            </div>

    </div>
</div>

@endsection
