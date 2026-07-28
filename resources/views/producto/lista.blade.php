@extends('/plantilla/base')

@section('dinamico')

         
<h1>LISTA DE LOS PRODUCTOS</h1>

<!-- Lista de productos -->
<section class="bg-gray-50 p-3 antialiased dark:bg-gray-900 sm:p-5">
    <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
        <div class="relative overflow-hidden bg-white shadow-md dark:bg-gray-800 sm:rounded-lg">

            <!-- Encabezado -->
            <div class="flex flex-col gap-4 p-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Productos
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Consulta y administra los productos registrados.
                    </p>
                </div>

                <a href="/producto/formulario"
                    class="inline-flex items-center justify-center rounded-lg bg-primary-700 px-4 py-2 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    <svg class="mr-2 h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Nuevo producto
                </a>
            </div>

            <!-- Buscador y acciones generales -->
            <div class="flex flex-col gap-3 border-t border-gray-200 p-4 dark:border-gray-700 md:flex-row md:items-center md:justify-between">
                <form class="w-full md:max-w-md" method="GET" action="">
                    <label for="buscar-producto" class="sr-only">Buscar producto</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>

                        <input type="text" id="buscar-producto" name="buscar"
                            placeholder="Buscar por nombre, categoría o proveedor..."
                            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 pl-10 text-sm text-gray-900 focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400">
                    </div>
                </form>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button"
                        class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-primary-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                        Seleccionar todos
                    </button>

                    <button type="button"
                        class="inline-flex items-center rounded-lg border border-red-200 bg-white px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-100 dark:border-red-900 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700">
                        Eliminar seleccionados
                    </button>
                </div>
            </div>

            <!-- Tabla -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="w-4 p-4">
                                <div class="flex items-center">
                                    <input id="checkbox-todos" type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary-600 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800">
                                    <label for="checkbox-todos" class="sr-only">Seleccionar todos</label>
                                </div>
                            </th>
                            <th scope="col" class="px-4 py-3">Producto</th>
                            <th scope="col" class="px-4 py-3">Categoría</th>
                            <th scope="col" class="px-4 py-3">Existencia</th>
                            <th scope="col" class="px-4 py-3">Precio</th>
                            <th scope="col" class="px-4 py-3">Proveedor</th>
                            <th scope="col" class="px-4 py-3 text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        {{-- PRODUCTO DE DEMOSTRACIÓN: elimina este <tr> cuando agregues tu @foreach --}}
                        <tr class="border-b border-gray-200 hover:bg-gray-100 dark:border-gray-700 dark:hover:bg-gray-700">
                            <td class="w-4 p-4">
                                <div class="flex items-center">
                                    <input id="producto-demo" type="checkbox"
                                        class="h-4 w-4 rounded border-gray-300 bg-gray-100 text-primary-600 focus:ring-2 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800">
                                    <label for="producto-demo" class="sr-only">Seleccionar producto</label>
                                </div>
                            </td>

                            <th scope="row" class="whitespace-nowrap px-4 py-3 font-medium text-gray-900 dark:text-white">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-600">
                                        <img src="https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front.svg"
                                            alt="Zapato deportivo de demostración"
                                            class="h-10 w-10 object-contain">
                                    </div>
                                    <div>
                                        <div class="font-semibold">Tenis deportivo negro</div>
                                        <div class="mt-1 text-xs font-normal text-gray-500 dark:text-gray-400">
                                            Calzado cómodo para uso diario
                                        </div>
                                    </div>
                                </div>
                            </th>

                            <td class="px-4 py-3">
                                <span class="rounded bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800 dark:bg-primary-900 dark:text-primary-300">
                                    Deportivo
                                </span>
                            </td>

                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                <div class="flex items-center">
                                    <span class="mr-2 inline-block h-3 w-3 rounded-full bg-green-500"></span>
                                    25 disponibles
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-4 py-3 font-semibold text-gray-900 dark:text-white">
                                $1,299.00
                            </td>

                            <td class="px-4 py-3 text-gray-900 dark:text-white">
                                Calzado del Centro
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="/producto/mostrar/1"
                                        class="inline-flex items-center rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-100 hover:text-primary-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:hover:text-white">
                                        Ver
                                    </a>

                                    <a href="/producto/edicion/1"
                                        class="inline-flex items-center rounded-lg bg-primary-700 px-3 py-2 text-xs font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700">
                                        Editar
                                    </a>

                                    <form action="/producto/eliminar/1" method="POST"
                                        onsubmit="return confirm('¿Seguro que deseas eliminar este producto?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-medium text-red-700 hover:bg-red-50 focus:outline-none focus:ring-4 focus:ring-red-100 dark:border-red-900 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        {{--
                        Aquí colocarás después:

                        @foreach ($productos as $producto)
                            <tr> ... </tr>
                        @endforeach
                        --}}
                    </tbody>
                </table>
            </div>

            <!-- Paginación estática de demostración -->
            <nav class="flex flex-col items-start justify-between gap-3 p-4 md:flex-row md:items-center"
                aria-label="Navegación de la tabla">
                <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                    Mostrando
                    <span class="font-semibold text-gray-900 dark:text-white">1</span>
                    de
                    <span class="font-semibold text-gray-900 dark:text-white">1</span>
                    producto
                </span>

                <ul class="inline-flex h-8 -space-x-px text-sm">
                    <li>
                        <button type="button" disabled
                            class="flex h-8 items-center justify-center rounded-l-lg border border-gray-300 bg-white px-3 leading-tight text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-600">
                            Anterior
                        </button>
                    </li>
                    <li>
                        <a href="#" aria-current="page"
                            class="flex h-8 items-center justify-center border border-primary-300 bg-primary-50 px-3 leading-tight text-primary-600 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">
                            1
                        </a>
                    </li>
                    <li>
                        <button type="button" disabled
                            class="flex h-8 items-center justify-center rounded-r-lg border border-gray-300 bg-white px-3 leading-tight text-gray-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-600">
                            Siguiente
                        </button>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</section>
@endsection