<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Zapatería Hermanos García')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Aplica el tema guardado antes de pintar la página (evita parpadeo)
        if (localStorage.getItem('tema') === 'oscuro') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>

<body class="bg-brand-beige dark:bg-gray-1000 transition-colors duration-200 flex flex-col min-h-screen">

    {{-- $apiUser, $esAdmin, $esMatriz, $puedeGestionar los inyecta
         MenuComposer en TODAS las vistas, no solo aquí. --}}
    @php
        $userName = trim((string) ($apiUser['nombre'] ?? '') . ' ' . (string) ($apiUser['apellido_paterno'] ?? ''));
        if ($userName === '') {
            $userName = (string) ($apiUser['usuario'] ?? 'Usuario');
        }
        $userEmail = (string) ($apiUser['correo'] ?? $apiUser['usuario'] ?? '');
        $rol = (string) ($apiUser['rol'] ?? '');
        $miSucursalNombre = data_get($apiUser, 'sucursal.nombre');
    @endphp

    {{-- Navbar superior --}}
    <nav class="fixed top-0 z-50 w-full bg-brand-dark/93 backdrop-blur-sm dark:bg-gray-800 border-b border-brand-brown/20 dark:border-gray-700">
        <div class="px-3 py-3 lg:px-5 lg:pl-3 flex items-center justify-between">
            <div class="flex items-center">
                <button id="toggleSidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-[#ebe2d6] dark:text-brand-cream rounded-lg hover:bg-brand-brown/10 dark:hover:bg-white/10">
                    <span class="sr-only">Abrir menú</span>
                    ☰
                </button>

                <a href="{{ url('/') }}" class="self-center text-1xl whitespace-nowrap ml-3 text-[#ebe2d6] dark:text-brand-cream flex items-center gap-2 font-serif">
                    <img src="{{ asset('images/HG-blanco.png') }}" alt="Logo" width="90">
                    Zapatería Hermanos García
                </a>
            </div>

            <div class="flex items-center gap-2">
                {{-- Switch de modo oscuro --}}
                <button id="toggleDarkMode" type="button"
                    class="p-2 text-sm rounded-lg text-brand-brown dark:text-brand-cream hover:bg-brand-brown/10 dark:hover:bg-white/10">
                    <span id="iconoSol" class="hidden dark:inline">☀️</span>
                    <span id="iconoLuna" class="inline dark:hidden">🌙</span>
                </button>

                <button type="button"
                    class="flex text-sm rounded-full"
                    data-dropdown-toggle="dropdown-user">
                    <img class="w-10 h-10 rounded-full"
                        src="{{ asset('images/carlos.png') }}">
                </button>
                <div class="hidden z-50 my-4 text-base list-none bg-white dark:bg-gray-700 divide-y divide-gray-100 dark:divide-gray-600 rounded-lg shadow"
                    id="dropdown-user">
                    <div class="px-4 py-3">
                        <p class="text-sm font-semibold dark:text-white">{{ $userName }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $userEmail ?: 'Usuario de la API' }}
                            @if ($rol)
                                · {{ $rol }}
                            @endif
                            @if ($miSucursalNombre)
                                · {{ $miSucursalNombre }}
                            @endif
                        </p>
                    </div>
                    <ul class="py-2">
                        <li>
                            <form action="{{ url('/logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200">Cerrar sesión</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    

    {{-- Sidebar lateral --}}
    <aside id="logo-sidebar"
        class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-bg-brand-dark/93 dark:bg-gray-800 border-r border-brand-brown/20 dark:border-gray-700">
        <div class="h-full px-3 pb-4 overflow-y-auto bg-brand-dark/85">
            <ul class="space-y-2 font-medium">
                <li class="font-serif m-2 mt-2"><a href="{{ url('/') }}" class="flex items-center p-2 rounded-lg text-white dark:text-brand-cream hover:bg-brand-brown/10 dark:hover:bg-white/10">Inicio</a></li>

                <li class="font-serif m-2 mt-2">
                    <button type="button" data-collapse-toggle="submenu-pedidos"
                        class="flex items-center justify-between w-full p-2 rounded-lg text-white dark:text-brand-cream hover:bg-brand-brown/10 dark:hover:bg-white/10">
                        <span>Pedidos</span>
                        <svg class="w-3 h-3 transition-transform" fill="none" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l4 4 4-4"/>
                        </svg>
                    </button>
                    <ul id="submenu-pedidos" class="hidden py-1 space-y-1">
                        <li class="font-serif m-2 mt-2"><a href="{{ url('/pedido') }}" class="flex items-center p-2 pl-4 ml-3 border-l-2 border-brand-brown/20 dark:border-gray-600 rounded-lg hover:bg-brand-brown/10 dark:hover:bg-white/10 text-sm text-white/80 dark:text-brand-cream/80">Todos</a></li>
                        @if ($puedeGestionar)
                            <li class="font-serif m-2 mt-2"><a href="{{ url('/pedido/pendientes') }}" class="flex items-center p-2 pl-4 ml-3 border-l-2 border-brand-brown/20 dark:border-gray-600 rounded-lg hover:bg-brand-brown/10 dark:hover:bg-white/10 text-sm text-white/80 dark:text-brand-cream/80">Pendientes</a></li>
                        @endif
                    </ul>
                </li>

                <li class="font-serif m-2 mt-2">
                    <button type="button" data-collapse-toggle="submenu-trayectos"
                        class="flex items-center justify-between w-full p-2 rounded-lg text-white dark:text-brand-cream hover:bg-brand-brown/10 dark:hover:bg-white/10">
                        <span>{{ $puedeGestionar ? 'Trayectos' : 'Mi trayecto' }}</span>
                        <svg class="w-3 h-3 transition-transform" fill="none" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l4 4 4-4"/>
                        </svg>
                    </button>
                    <ul id="submenu-trayectos" class="hidden py-1 space-y-1">
                        <li class="font-serif m-2 mt-2"><a href="{{ url('/trayecto/lista') }}" class="flex items-center p-2 pl-4 ml-3 border-l-2 border-brand-brown/20 dark:border-gray-600 rounded-lg hover:bg-brand-brown/10 dark:hover:bg-white/10 text-sm text-white/80 dark:text-brand-cream/80">Lista</a></li>
                        <li class="font-serif m-2 mt-2"><a href="{{ url('/trayecto/flota') }}" class="flex items-center p-2 pl-4 ml-3 border-l-2 border-brand-brown/20 dark:border-gray-600 rounded-lg hover:bg-brand-brown/10 dark:hover:bg-white/10 text-sm text-white/80 dark:text-brand-cream/80">Mapa de flota</a></li>
                    </ul>
                </li>

                <li class="font-serif m-2 mt-2"><a href="{{ url('/inventario') }}" class="flex items-center p-2 rounded-lg text-white dark:text-brand-cream hover:bg-brand-brown/10 dark:hover:bg-white/10">{{ $puedeGestionar ? 'Inventario' : 'Mi inventario' }}</a></li>

                @if ($esAdmin)
                    <li class="font-serif m-2 mt-2"><a href="{{ url('/producto') }}" class="flex items-center p-2 rounded-lg text-white dark:text-brand-cream hover:bg-brand-brown/10 dark:hover:bg-white/10">Productos</a></li>
                    <li class="font-serif m-2 mt-2"><a href="{{ url('/sucursal') }}" class="flex items-center p-2 rounded-lg text-white dark:text-brand-cream hover:bg-brand-brown/10 dark:hover:bg-white/10">Sucursales</a></li>
                    <li class="font-serif m-2 mt-2"><a href="{{ url('/chofer') }}" class="flex items-center p-2 rounded-lg text-white dark:text-brand-cream hover:bg-brand-brown/10 dark:hover:bg-white/10">Choferes</a></li>
                    <li class="font-serif m-2 mt-2"><a href="{{ url('/carro') }}" class="flex items-center p-2 rounded-lg text-white dark:text-brand-cream hover:bg-brand-brown/10 dark:hover:bg-white/10">Carros</a></li>
                    <li class="font-serif m-2 mt-2"><a href="{{ url('/empleado') }}" class="flex items-center p-2 rounded-lg text-white dark:text-brand-cream hover:bg-brand-brown/10 dark:hover:bg-white/10">Empleados</a></li>
                    <li class="font-serif m-2 mt-2"><a href="{{ url('/marca') }}" class="flex items-center p-2 rounded-lg text-white dark:text-brand-cream hover:bg-brand-brown/10 dark:hover:bg-white/10">Marcas</a></li>
                    <li class="font-serif m-2 mt-2"><a href="{{ url('/proveedor') }}" class="flex items-center p-2 rounded-lg text-white dark:text-brand-cream hover:bg-brand-brown/10 dark:hover:bg-white/10">Proveedores</a></li>
                @endif
            </ul>
        </div>
    </aside>


    {{-- Contenido dinámico --}}
    <main id="mainContent" class="p-4 mt-20 flex-1 transition-all duration-300">
        @yield('dinamico')
    </main>

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

        toggleBtn.addEventListener('click', () => {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            isHidden ? openSidebar() : closeSidebar();
        });

        sidebar.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                closeSidebar();
            });
        });

        closeSidebar();

        // Modo oscuro: recuerda la preferencia del usuario en localStorage
        const toggleDarkBtn = document.getElementById('toggleDarkMode');
        const html = document.documentElement;

        toggleDarkBtn.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('tema', html.classList.contains('dark') ? 'oscuro' : 'claro');
        });
    </script>

    <footer class="p-4 bg-brand-dark/90 sm:p-6">
        <div class="mx-auto max-w-screen-xl">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                     <a href="{{ url('/') }}" class="flex flex-col items-center md:items-start">
                        <img src="{{ asset('images/Logo-blanco.png') }}" class="h-25  p-1" alt="HG Logo" />
                        <span class="font-script text-2xl mt-1 text-[#ebe2d6]">Calzado que deja huella</span>
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-2">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-[#ebe2d6] uppercase">Navegación</h2>
                        <ul class="text-brand-cream/70">
                            <li class="mb-4 text-[#ebe2d6]"><a href="{{ url('/pedido') }}" class="hover:underline">Pedidos</a></li>
                            <li class="font-serif m-2 mt-2"><a href="{{ url('/inventario') }}" class="hover:underline text-[#ebe2d6]">Inventario</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-[#ebe2d6] uppercase">Contacto</h2>
                        <ul class="text-brand-cream/70">
                            <li class="mb-4 text-[#ebe2d6]"><a href="mailto:red-ivo@solutions.com" class="hover:underline">red-ivo@solutions.com</a></li>
                            <li class="mb-4 text-[#ebe2d6]"><a href="#" class="hover:underline text-[#ebe2d6]">Sucursales</a></li>
                            <li class="mb-4 text-[#ebe2d6]"><a href="#" class="hover:underline text-[#ebe2d6]">Politica de privacidad</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-brand-cream/20 lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-brand-cream/70 sm:text-center text-[#ebe2d6]">© {{ date('Y') }} Zapatería Hermanos García. Todos los derechos reservados.</span>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

</body>
</html>