@extends('/plantilla/base')

@section('dinamico')

<h1 class="text-2xl font-semibold text-gray-800 mb-4">Nuevo producto</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ url('/producto/guardar') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @csrf

        <div class="sm:col-span-2">
            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="sm:col-span-2">
            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="marca_id" class="block text-sm font-medium text-gray-700">Marca</label>
                <button type="button" id="toggleNuevaMarca" class="text-xs text-blue-600 hover:underline">+ Nueva marca</button>
            </div>
            <select name="marca_id" id="marca_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">-- Selecciona --</option>
                @foreach ($marcas ?? [] as $marca)
                    <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                @endforeach
            </select>

            <div id="panelNuevaMarca" class="hidden mt-2 p-3 border border-gray-200 rounded-lg bg-gray-50 space-y-2">
                <input type="text" id="nuevaMarcaNombre" placeholder="Nombre de la marca" class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
                <select id="nuevaMarcaProveedor" class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-sm">
                    <option value="">-- Proveedor --</option>
                    @foreach ($proveedores ?? [] as $proveedor)
                        <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                    @endforeach
                </select>
                <p id="errorNuevaMarca" class="hidden text-xs text-red-600"></p>
                <div class="flex gap-2">
                    <button type="button" id="guardarNuevaMarca" class="text-sm bg-blue-600 text-white px-3 py-1.5 rounded-lg hover:bg-blue-700">
                        Agregar
                    </button>
                    <button type="button" id="cancelarNuevaMarca" class="text-sm px-3 py-1.5 rounded-lg border border-gray-300 hover:bg-gray-50">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>

        <div>
            <label for="proveedor_id" class="block text-sm font-medium text-gray-700 mb-1">Proveedor</label>
            <select name="proveedor_id" id="proveedor_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">-- Selecciona --</option>
                @foreach ($proveedores ?? [] as $proveedor)
                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="precio" class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
            <input type="number" step="0.01" name="precio" id="precio" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="modelo" class="block text-sm font-medium text-gray-700 mb-1">Modelo</label>
            <input type="text" name="modelo" id="modelo" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="color" class="block text-sm font-medium text-gray-700 mb-1">Color</label>
            <input type="text" name="color" id="color" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div>
            <label for="sexo" class="block text-sm font-medium text-gray-700 mb-1">Sexo</label>
            <select name="sexo" id="sexo" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Femenino">Femenino</option>
                <option value="Masculino">Masculino</option>
            </select>
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <label for="categoria_select" class="block text-sm font-medium text-gray-700">Categoría</label>
                <button type="button" id="toggleNuevaCategoria" class="text-xs text-blue-600 hover:underline">+ Nueva categoría</button>
            </div>
            <select name="categoria" id="categoria_select" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Selecciona --</option>
                @foreach ($categorias ?? [] as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
            <input type="text" name="categoria" id="categoria_nueva" placeholder="Escribe la nueva categoría" disabled
                class="hidden w-full border border-gray-300 rounded-lg px-3 py-2 text-sm mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label for="estatus" class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" id="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Activo">Activo</option>
                <option value="Agotado">Agotado</option>
            </select>
        </div>

        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tallas disponibles</label>
            <p class="text-xs text-gray-500 mb-2">Se crea un producto por cada talla marcada — el resto de los datos de arriba se usan una sola vez.</p>

            <div class="flex flex-wrap items-end gap-2 mb-3">
                <div>
                    <label for="rango_desde" class="block text-xs text-gray-500 mb-1">De</label>
                    <select id="rango_desde" class="border border-gray-300 rounded-lg px-2 py-1.5 text-sm">
                        @foreach ($tallas ?? [] as $talla)
                            <option value="{{ $talla }}">{{ $talla }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="rango_hasta" class="block text-xs text-gray-500 mb-1">Hasta</label>
                    <select id="rango_hasta" class="border border-gray-300 rounded-lg px-2 py-1.5 text-sm">
                        @foreach ($tallas ?? [] as $talla)
                            <option value="{{ $talla }}">{{ $talla }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" id="marcarRango" class="px-3 py-1.5 rounded-lg border border-gray-300 text-sm hover:bg-gray-50">Marcar rango</button>
                <button type="button" id="limpiarTallas" class="px-3 py-1.5 rounded-lg border border-gray-300 text-sm hover:bg-gray-50">Limpiar</button>
            </div>

            <div class="grid grid-cols-4 sm:grid-cols-6 gap-2 border border-gray-300 rounded-lg p-3">
                @foreach ($tallas ?? [] as $talla)
                    <label class="flex items-center gap-1 text-sm">
                        <input type="checkbox" name="tallas[]" value="{{ $talla }}" class="talla-checkbox" data-talla="{{ $talla }}">
                        {{ $talla }}
                    </label>
                @endforeach
            </div>
        </div>

        <div>
            <label for="imagen1" class="block text-sm font-medium text-gray-700 mb-1">Imagen 1</label>
            <input type="file" name="imagen1" id="imagen1" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
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
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('marcarRango').addEventListener('click', () => {
        const desde = parseFloat(document.getElementById('rango_desde').value);
        const hasta = parseFloat(document.getElementById('rango_hasta').value);
        const min = Math.min(desde, hasta);
        const max = Math.max(desde, hasta);

        document.querySelectorAll('.talla-checkbox').forEach(cb => {
            const valor = parseFloat(cb.dataset.talla);
            if (valor >= min && valor <= max) {
                cb.checked = true;
            }
        });
    });

    document.getElementById('limpiarTallas').addEventListener('click', () => {
        document.querySelectorAll('.talla-checkbox').forEach(cb => cb.checked = false);
    });

    const panelNuevaMarca = document.getElementById('panelNuevaMarca');
    const nombreNuevaMarca = document.getElementById('nuevaMarcaNombre');
    const proveedorNuevaMarca = document.getElementById('nuevaMarcaProveedor');
    const errorNuevaMarca = document.getElementById('errorNuevaMarca');

    function cerrarPanelNuevaMarca() {
        panelNuevaMarca.classList.add('hidden');
        nombreNuevaMarca.value = '';
        proveedorNuevaMarca.value = '';
        errorNuevaMarca.classList.add('hidden');
    }

    document.getElementById('toggleNuevaMarca').addEventListener('click', () => {
        panelNuevaMarca.classList.toggle('hidden');
    });

    document.getElementById('cancelarNuevaMarca').addEventListener('click', cerrarPanelNuevaMarca);

    document.getElementById('guardarNuevaMarca').addEventListener('click', async () => {
        const nombre = nombreNuevaMarca.value.trim();
        const proveedorId = proveedorNuevaMarca.value;

        if (!nombre || !proveedorId) {
            errorNuevaMarca.textContent = 'Escribe un nombre y elige un proveedor.';
            errorNuevaMarca.classList.remove('hidden');
            return;
        }

        try {
            const respuesta = await fetch('{{ url('/marca/guardar-rapido') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                },
                body: JSON.stringify({ nombre, proveedor_id: proveedorId }),
            });

            const datos = await respuesta.json();

            if (!respuesta.ok) {
                errorNuevaMarca.textContent = datos.error || 'No se pudo guardar la marca.';
                errorNuevaMarca.classList.remove('hidden');
                return;
            }

            const selectMarca = document.getElementById('marca_id');
            const opcion = document.createElement('option');
            opcion.value = datos.id;
            opcion.textContent = datos.nombre;
            selectMarca.appendChild(opcion);
            selectMarca.value = datos.id;

            cerrarPanelNuevaMarca();
        } catch (error) {
            errorNuevaMarca.textContent = 'Error de conexión al guardar la marca.';
            errorNuevaMarca.classList.remove('hidden');
        }
    });

    const selectCategoria = document.getElementById('categoria_select');
    const inputCategoria = document.getElementById('categoria_nueva');
    const toggleCategoria = document.getElementById('toggleNuevaCategoria');

    function modoNuevaCategoria(activar) {
        selectCategoria.disabled = activar;
        selectCategoria.classList.toggle('hidden', activar);
        inputCategoria.disabled = !activar;
        inputCategoria.classList.toggle('hidden', !activar);
        toggleCategoria.textContent = activar ? 'Elegir de la lista' : '+ Nueva categoría';
        if (activar) {
            inputCategoria.focus();
        }
    }

    toggleCategoria.addEventListener('click', () => {
        modoNuevaCategoria(inputCategoria.classList.contains('hidden'));
    });
});
</script>

@endsection
