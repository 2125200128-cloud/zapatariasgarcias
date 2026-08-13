@extends('/plantilla/base')

@section('dinamico')

{{-- Encabezado de la página --}}
<div class="flex flex-wrap items-center justify-between gap-2 mb-10 mt-4 px-4">
    <div class="flex items-center gap-4">
        <a href="{{ url('/') }}" class="shrink-0 transition-transform hover:scale-105">
            <img src="{{ asset('images/products-formulario.png') }}" alt="Inicio-productos" class="w-18 h-18 object-contain">
        </a>
        <div>
            <h1 class="text-4xl font-serif text-[#17181d] px-4">Productos</h1>
            <p class="font-serif text-brand-black-coffe px-4">Gestiona el catálogo de calzado y sus variantes por grupo</p>
        </div>
    </div>

    <div class="flex items-center gap-3">
        <span class="text-sm font-medium text-[#3d3228] bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200">
            {{ count($grupos ?? []) }} grupos visibles
        </span>
        <a href="{{ url('/producto/formulario') }}" class="bg-brand-black-coffe text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-brand-caramel transition-colors shadow-sm">
            + Nuevo producto
        </a>
    </div>
</div>

{{-- Mensajes de Notificación --}}
@if (session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">
        {{ $errors->first() }}
    </div>
@endif


{{-- Tabla Principal --}}
<div class="bg-white rounded-xl shadow-sm overflow-x-auto border border-gray-200/80">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Producto</th>
                <th class="px-4 py-3">Marca</th>
                <th class="px-4 py-3">Precio</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3 text-right">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse ($grupos ?? [] as $grupo)
                @php
                    // Primer registro del grupo para extraer datos generales
                    $primero = is_array($grupo) ? reset($grupo) : $grupo->first();
                    
                    // Extraer nombres de relaciones
                    $marca = data_get($primero, 'marca.nombre') ?? data_get($primero, 'marca', '—');
                    $proveedor = data_get($primero, 'proveedor.nombre') ?? data_get($primero, 'proveedor', '—');

                    // Imagen Principal
                    $img1 = data_get($primero, 'imagen_1') ?? data_get($primero, 'imagen1') ?? asset('images/sin-imagen.jpg');
                    if (!str_starts_with($img1, 'http') && $img1 !== asset('images/sin-imagen.jpg')) {
                        $img1 = asset($img1);
                    }

                    // Recopilar todas las tallas del grupo
                    $tallas = [];
                    foreach ($grupo as $v) {
                        $tallas[] = data_get($v, 'talla', '—');
                    }

                    // Paquete de datos que enviamos al Modal
                    $datosModal = [
                        'nombre'    => data_get($primero, 'nombre', '—'),
                        'marca'     => $marca,
                        'proveedor' => $proveedor,
                        'precio'    => data_get($primero, 'precio', 0),
                        'categoria' => data_get($primero, 'categoria', '—'),
                        'estatus'   => data_get($primero, 'estatus', '—'),
                        'imagen_1'  => data_get($primero, 'imagen_1') ?? data_get($primero, 'imagen1'),
                        'imagen_2'  => data_get($primero, 'imagen_2') ?? data_get($primero, 'imagen2'),
                        'imagen_3'  => data_get($primero, 'imagen_3') ?? data_get($primero, 'imagen3'),
                        'tallas'    => $tallas,
                    ];
                @endphp
                <tr class="hover:bg-gray-50/60 transition-colors">
                    
                    {{-- Miniatura + Nombre + Categoría --}}
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $img1 }}" alt="Foto producto" class="h-11 w-11 rounded-lg object-cover border border-gray-200 flex-shrink-0 bg-gray-50 shadow-xs">
                            <div>
                                <p class="font-semibold text-gray-900">{{ data_get($primero, 'nombre', '—') }}</p>
                                <p class="text-xs text-gray-500 capitalize">{{ data_get($primero, 'categoria', 'Sin categoría') }}</p>
                            </div>
                        </div>
                    </td>

                    {{-- Marca --}}
                    <td class="px-4 py-3 font-medium text-gray-700 capitalize">{{ $marca }}</td>

                    {{-- Precio --}}
                    <td class="px-4 py-3 font-bold text-gray-900">
                        ${{ number_format((float) data_get($primero, 'precio', 0), 2) }}
                    </td>

                    {{-- Estatus --}}
                    <td class="px-4 py-3">
                        @if (data_get($primero, 'estatus') === 'Activo')
                            <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">Activo</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20">Inactivo</span>
                        @endif
                    </td>

                    {{-- Los 3 Íconos de Acciones Homologados --}}
                    <td class="px-4 py-3 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1">
                            
                            {{-- 1. Botón VER DETALLE (Modal - Ícono Ojo) --}}
                            <button type="button" 
                                    data-producto='@json($datosModal)'
                                    onclick="abrirModalDetalle(this)" 
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition-colors" 
                                    title="Ver detalle">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            {{-- 2. Botón EDITAR (Ícono Lápiz) --}}
                            <a href="{{ url('/producto/editar/' . data_get($primero, 'id', '')) }}" 
                               class="p-1.5 text-gray-500 hover:bg-gray-100 hover:text-blue-600 rounded-lg transition-colors" 
                               title="Editar producto">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>

                            {{-- 3. Botón ELIMINAR (Ícono Papelera) --}}
                            <a href="{{ url('/producto/mostrar/' . data_get($primero, 'id', '')) }}" 
                               class="p-1.5 text-gray-500 hover:bg-gray-100 hover:text-red-600 rounded-lg transition-colors" 
                               title="Eliminar producto">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </a>

                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                        No hay productos registrados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Modal de Detalle de Producto --}}
<div id="modalDetalle" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="w-full max-w-2xl rounded-2xl bg-white p-6 shadow-2xl transition-all">
        
        {{-- Header Modal --}}
        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
            <h3 id="modal-titulo" class="text-xl font-bold text-gray-900">Detalles del Producto</h3>
            <button onclick="cerrarModalDetalle()" class="rounded-lg p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-900 transition-colors">
                ✕
            </button>
        </div>

        {{-- Galería de 3 Imágenes --}}
        <div class="my-4">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Galería de Imágenes</p>
            <div class="grid grid-cols-3 gap-3">
                <div class="h-28 rounded-xl border border-gray-200 overflow-hidden bg-gray-50 shadow-xs">
                    <img id="modal-img1" src="" class="w-full h-full object-cover">
                </div>
                <div class="h-28 rounded-xl border border-gray-200 overflow-hidden bg-gray-50 shadow-xs">
                    <img id="modal-img2" src="" class="w-full h-full object-cover">
                </div>
                <div class="h-28 rounded-xl border border-gray-200 overflow-hidden bg-gray-50 shadow-xs">
                    <img id="modal-img3" src="" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        {{-- Datos Generales --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm bg-gray-50 p-4 rounded-xl border border-gray-100">
            <div>
                <span class="block text-xs text-gray-500">Marca</span>
                <strong id="modal-marca" class="text-gray-900 capitalize"></strong>
            </div>
            <div>
                <span class="block text-xs text-gray-500">Categoría</span>
                <strong id="modal-categoria" class="text-gray-900 capitalize"></strong>
            </div>
            <div>
                <span class="block text-xs text-gray-500">Precio</span>
                <strong id="modal-precio" class="text-green-600 font-bold"></strong>
            </div>
            <div class="col-span-2 sm:col-span-3">
                <span class="block text-xs text-gray-500">Proveedor</span>
                <strong id="modal-proveedor" class="text-gray-900"></strong>
            </div>
        </div>

        {{-- Tallas --}}
        <div class="mt-4">
            <span class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Tallas Disponibles</span>
            <div id="modal-tallas" class="flex flex-wrap gap-1.5">
                {{-- Se llena dinámicamente con JavaScript --}}
            </div>
        </div>

        {{-- Footer Modal --}}
        <div class="mt-6 flex justify-end">
            <button onclick="cerrarModalDetalle()" class="px-4 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors">
                Cerrar
            </button>
        </div>
    </div>
</div>

<script>
    function abrirModalDetalle(btn) {
        const p = JSON.parse(btn.getAttribute('data-producto'));

        // Textos
        document.getElementById('modal-titulo').innerText = p.nombre || 'Producto';
        document.getElementById('modal-marca').innerText = p.marca || '—';
        document.getElementById('modal-categoria').innerText = p.categoria || '—';
        document.getElementById('modal-proveedor').innerText = p.proveedor || '—';
        document.getElementById('modal-precio').innerText = '$' + parseFloat(p.precio || 0).toFixed(2);

        // Imágenes
        const imgPlaceholder = "{{ asset('images/sin-imagen.jpg') }}";
        
        ['1', '2', '3'].forEach(num => {
            let imgUrl = p['imagen_' + num] || imgPlaceholder;
            if (imgUrl !== imgPlaceholder && !imgUrl.startsWith('http')) {
                imgUrl = "{{ asset('') }}" + imgUrl;
            }
            document.getElementById('modal-img' + num).src = imgUrl;
        });

        // Tallas
        const contenedorTallas = document.getElementById('modal-tallas');
        contenedorTallas.innerHTML = '';

        if (Array.isArray(p.tallas) && p.tallas.length > 0) {
            p.tallas.forEach(talla => {
                const badge = document.createElement('span');
                badge.className = 'px-2.5 py-1 text-xs font-semibold bg-blue-50 text-blue-700 rounded-md border border-blue-100 shadow-xs';
                badge.innerText = talla;
                contenedorTallas.appendChild(badge);
            });
        } else {
            contenedorTallas.innerHTML = '<span class="text-xs text-gray-400 italic">Sin tallas registradas</span>';
        }

        // Mostrar Modal
        document.getElementById('modalDetalle').classList.remove('hidden');
    }

    function cerrarModalDetalle() {
        document.getElementById('modalDetalle').classList.add('hidden');
    }
</script>

@endsection