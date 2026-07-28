@extends('/plantilla/base')

@section('dinamico')

<h1 class="text-2xl font-semibold text-gray-800 mb-4">Editar producto</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ url('/producto/actualizar/' . $producto->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @csrf

        <div class="sm:col-span-2">
            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{ $producto->nombre }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="sm:col-span-2">
            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>{{ $producto->descripcion }}</textarea>
        </div>

        <div>
            <label for="marca_id" class="block text-sm font-medium text-gray-700 mb-1">Marca</label>
            <select name="marca_id" id="marca_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">-- Selecciona --</option>
                @foreach ($marcas ?? [] as $marca)
                    <option value="{{ $marca->id }}" @selected($producto->marca_id == $marca->id)>{{ $marca->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="proveedor_id" class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
            <select name="proveedor_id" id="proveedor_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">-- Selecciona --</option>
                @foreach ($proveedores ?? [] as $proveedor)
                    <option value="{{ $proveedor->id }}" @selected($producto->proveedor_id == $proveedor->id)>{{ $proveedor->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="precio" class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
            <input type="number" step="0.01" name="precio" id="precio" value="{{ $producto->precio }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="modelo" class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
            <input type="text" name="modelo" id="modelo" value="{{ $producto->modelo }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
            <input type="text" name="color" id="color" value="{{ $producto->color }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="sexo" class="block text-sm font-medium text-gray-700 mb-1">Sexo</label>
            <select name="sexo" id="sexo" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Femenino" @selected($producto->sexo === 'Femenino')>Femenino</option>
                <option value="Masculino" @selected($producto->sexo === 'Masculino')>Masculino</option>
            </select>
        </div>

        <div>
            <label for="categoria" class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
            <input type="text" name="categoria" id="categoria" value="{{ $producto->categoria }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="talla" class="block text-sm font-medium text-gray-700 mb-1">Talla</label>
            <input type="text" name="talla" id="talla" value="{{ $producto->talla }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="estatus" class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" id="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Activo" @selected($producto->estatus === 'Activo')>Activo</option>
                <option value="Agotado" @selected($producto->estatus === 'Agotado')>Agotado</option>
            </select>
        </div>

        <div>
            <label for="imagen1" class="block text-sm font-medium text-gray-700 mb-1">Imagen 1</label>
            <input type="file" name="imagen1" id="imagen1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label for="imagen2" class="block text-sm font-medium text-gray-700 mb-1">Imagen 2</label>
            <input type="file" name="imagen2" id="imagen2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div>
            <label for="imagen3" class="block text-sm font-medium text-gray-700 mb-1">Imagen 3</label>
            <input type="file" name="imagen3" id="imagen3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>

        <div class="sm:col-span-2">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar cambios</button>
        </div>
    </form>
</div>

@endsection
