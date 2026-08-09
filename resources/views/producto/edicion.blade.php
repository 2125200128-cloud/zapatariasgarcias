@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-4xl rounded-lg bg-white p-6 shadow">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Editar producto</h1>
            <p class="text-sm text-gray-500">Actualiza la información del producto en la API.</p>
        </div>
        <a href="{{ url('/producto') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Volver</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ url('/producto/actualizar/' . data_get($producto, 'id', '')) }}" method="POST" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2">
        @csrf

        <div class="md:col-span-2">
            <label class="mb-1 block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" name="nombre" value="{{ old('nombre', data_get($producto, 'nombre', '')) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2" required>
        </div>

        <div class="md:col-span-2">
            <label class="mb-1 block text-sm font-medium text-gray-700">Descripción</label>
            <textarea name="descripcion" rows="3" class="w-full rounded-lg border border-gray-300 px-3 py-2">{{ old('descripcion', data_get($producto, 'descripcion', '')) }}</textarea>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Marca</label>
            <select name="marca_id" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                <option value="">Selecciona una marca</option>
                @foreach ($marcas as $marca)
                    <option value="{{ data_get($marca, 'id', '') }}" {{ old('marca_id', data_get($producto, 'marca_id', '')) == data_get($marca, 'id', '') ? 'selected' : '' }}>
                        {{ data_get($marca, 'nombre', 'Marca') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Proveedor</label>
            <select name="proveedor_id" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                <option value="">Selecciona un proveedor</option>
                @foreach ($proveedores as $proveedor)
                    <option value="{{ data_get($proveedor, 'id', '') }}" {{ old('proveedor_id', data_get($producto, 'proveedor_id', '')) == data_get($proveedor, 'id', '') ? 'selected' : '' }}>
                        {{ data_get($proveedor, 'nombre', 'Proveedor') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Precio</label>
            <input type="number" step="0.01" name="precio" value="{{ old('precio', data_get($producto, 'precio', '')) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2" required>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Modelo</label>
            <input type="text" name="modelo" value="{{ old('modelo', data_get($producto, 'modelo', '')) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Color</label>
            <input type="text" name="color" value="{{ old('color', data_get($producto, 'color', '')) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Sexo</label>
            <input type="text" name="sexo" value="{{ old('sexo', data_get($producto, 'sexo', '')) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Categoría</label>
            <input type="text" name="categoria" value="{{ old('categoria', data_get($producto, 'categoria', '')) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Talla</label>
            <input type="text" name="talla" value="{{ old('talla', data_get($producto, 'talla', '')) }}" class="w-full rounded-lg border border-gray-300 px-3 py-2">
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700">Estatus</label>
            <select name="estatus" class="w-full rounded-lg border border-gray-300 px-3 py-2">
                <option value="Activo" {{ old('estatus', data_get($producto, 'estatus', '')) === 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Agotado" {{ old('estatus', data_get($producto, 'estatus', '')) === 'Agotado' ? 'selected' : '' }}>Agotado</option>
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
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Actualizar</button>
        </div>
    </form>
</div>

@endsection
