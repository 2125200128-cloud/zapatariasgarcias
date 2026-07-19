<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('titulo', 'Zapatería Hermanos García')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    {{-- Navbar --}}
    <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">

            <div class="flex items-center justify-between">

                <div class="flex items-center">

                    <button
                        data-drawer-target="logo-sidebar"
                        data-drawer-toggle="logo-sidebar"
                        aria-controls="logo-sidebar"
                        type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100">

                        <span class="sr-only">Abrir menú</span>

                        <svg class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>

                    </button>

                    <span class="self-center text-2xl font-semibold whitespace-nowrap ml-2">
                        Zapatería Hermanos García
                    </span>

                </div>

                <div class="flex items-center">

                    <button type="button"
                        class="flex text-sm rounded-full"
                        data-dropdown-toggle="dropdown-user">

                        <img class="w-10 h-10 rounded-full"
                            src="https://flowbite.com/docs/images/people/profile-picture-5.jpg">

                    </button>

                    <div
                        class="hidden z-50 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow"
                        id="dropdown-user">

                        <div class="px-4 py-3">

                            <p class="text-sm font-semibold">
                                Administrador
                            </p>

                            <p class="text-sm text-gray-500">
                                admin@zapateria.com
                            </p>

                        </div>

                        <ul class="py-2">

                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100">
                                    Perfil
                                </a>
                            </li>

                            <li>
                                <a href="#"
                                    class="block px-4 py-2 hover:bg-gray-100">
                                    Cerrar sesión
                                </a>
                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </div>
    </nav>

    {{-- Sidebar --}}
    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0">

        <div class="h-full px-3 pb-4 overflow-y-auto bg-white">

            <ul class="space-y-2 font-medium">

                <li>
                    <a href="/"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                        <span>Inicio</span>
                    </a>
                </li>

                <li>
                    <a href="/empleado"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                        <span>Empleados</span>
                    </a>
                </li>

                <li>
                    <a href="/cliente"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                        <span>Clientes</span>
                    </a>
                </li>

                <li>
                    <a href="/pedido"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                        <span>Pedidos</span>
                    </a>
                </li>

                <li>
                    <a href="/producto"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                        <span>Productos</span>
                    </a>
                </li>

                <li>
                    <a href="/proveedor"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                        <span>Proveedores</span>
                    </a>
                </li>

                <li>
                    <a href="/sucursal"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                        <span>Sucursales</span>
                    </a>
                </li>

                <li>
                    <a href="/carro"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                        <span>Carros</span>
                    </a>
                </li>

                <li>
                    <a href="/chofer"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                        <span>Choferes</span>
                    </a>
                </li>

                <li>
                    <a href="/trayecto"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                        <span>Trayectos</span>
                    </a>
                </li>

                <li>
                    <a href="/inventario"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                        <span>Inventario</span>
                    </a>
                </li>

                <li>
                    <a href="/marca"
                        class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                        <span>Marcas</span>
                    </a>
                </li>

            </ul>

        </div>

    </aside>

    {{-- Contenido --}}
    <main class="p-4 sm:ml-64 mt-16">

        @yield('dinamico')

    </main>

</body>

</html>