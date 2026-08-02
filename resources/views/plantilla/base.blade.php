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
        <div class="px-3 py-3 lg:px-5 lg:pl-3 flex items-center justify-between">

            <div class="flex items-center">
                <!-- Botón hamburguesa -->
                <button id="toggleSidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100">
                    <span class="sr-only">Abrir menú</span>
                    ☰
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
                <div class="hidden z-50 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow"
                    id="dropdown-user">
                    @php
                        $empleadoActual = Auth::guard('empleado')->user();
                    @endphp
                    <div class="px-4 py-3">
                        <p class="text-sm font-semibold">{{ $empleadoActual->nombre }} {{ $empleadoActual->apellido_paterno }}</p>
                        <p class="text-sm text-gray-500">{{ $empleadoActual->usuario }} · {{ $empleadoActual->rol }}</p>
                    </div>
                    <ul class="py-2">
                        <li>
                            <form action="{{ url('/logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    {{-- Sidebar --}}
    {{-- Ya NO lleva "sm:translate-x-0": el estado lo maneja 100% el JS --}}
    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200">

        <div class="h-full px-3 pb-4 overflow-y-auto bg-white">
            @php
                $empleadoSidebar = Auth::guard('empleado')->user();
                $puedeAsignar = $empleadoSidebar->esAdministrador() || $empleadoSidebar->esMatriz();
            @endphp
            <ul class="space-y-2 font-medium">
                <li><a href="{{ url('/') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Inicio</a></li>
                @if ($empleadoSidebar->esAdministrador())
                    <li><a href="{{ url('/empleado') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Empleados</a></li>
                @endif
                <li><a href="{{ url('/pedido') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Pedidos</a></li>
                @if ($puedeAsignar)
                    <li><a href="{{ url('/pedido/pendientes') }}" class="flex items-center p-2 pl-4 ml-3 border-l-2 border-gray-200 rounded-lg hover:bg-gray-100 hover:border-gray-300 text-sm text-gray-600">Pendientes de aceptar</a></li>
                @endif
                @if ($empleadoSidebar->esAdministrador())
                    <li><a href="{{ url('/producto') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Productos</a></li>
                    <li><a href="{{ url('/proveedor') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Proveedores</a></li>
                    <li><a href="{{ url('/sucursal') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Sucursales</a></li>
                    <li><a href="{{ url('/carro') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Carros</a></li>
                    <li><a href="{{ url('/chofer') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Choferes</a></li>
                @endif
                <li><a href="{{ url('/trayecto/lista') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Trayectos</a></li>
                <li><a href="{{ url('/trayecto/flota') }}" class="flex items-center p-2 pl-4 ml-3 border-l-2 border-gray-200 rounded-lg hover:bg-gray-100 hover:border-gray-300 text-sm text-gray-600">Mapa de flota</a></li>
                <li><a href="{{ url('/inventario') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Inventario</a></li>
                @if ($empleadoSidebar->esAdministrador())
                    <li><a href="{{ url('/marca') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-100">Marcas</a></li>
                @endif
            </ul>
        </div>
    </aside>

    {{-- Contenido --}}
    {{-- id="mainContent" para poder ajustar el margen dinámicamente con JS --}}
    <main id="mainContent" class="p-4 mt-16 transition-all duration-300">
        @yield('dinamico')
    </main>

    {{-- Script para toggle con Flowbite/Tailwind --}}
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('logo-sidebar');
        const mainContent = document.getElementById('mainContent');

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            mainContent.classList.remove('sm:ml-64');
        }

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            mainContent.classList.add('sm:ml-64');
        }

        // Mostrar/ocultar sidebar con el botón hamburguesa
        toggleBtn.addEventListener('click', () => {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            isHidden ? openSidebar() : closeSidebar();
        });

        // Ocultar sidebar al dar clic en cualquier enlace del menú
        sidebar.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                closeSidebar();
            });
        });

        // Estado inicial: cerrado, mostrando solo el botón hamburguesa
        closeSidebar();
    </script>

    <!-- Flowbite JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

</body>
</html>