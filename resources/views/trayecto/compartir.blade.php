<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Compartir ubicación — Trayecto #{{ $trayecto->id }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-lg shadow p-6 w-full max-w-sm text-center">
        <h1 class="text-xl font-semibold mb-1">Trayecto #{{ $trayecto->id }}</h1>
        <p class="text-gray-500 mb-4">Pedido #{{ $trayecto->pedido_id }} — {{ $trayecto->estatus }}</p>

        <button id="btnCompartir"
            class="w-full bg-blue-600 text-white font-medium py-3 rounded-lg hover:bg-blue-700">
            Compartir mi ubicación
        </button>

        <p id="estadoTexto" class="text-sm text-gray-500 mt-4">
            Toca el botón para empezar a enviar tu ubicación mientras haces la entrega.
        </p>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const trayectoId = {{ $trayecto->id }};
        const baseUrl = '{{ url('/') }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const btn = document.getElementById('btnCompartir');
        const estado = document.getElementById('estadoTexto');

        const INTERVALO_MINIMO_MS = 8000;
        let ultimoEnvio = 0;
        let watchId = null;

        function enviarPosicion(lat, lng) {
            fetch(`${baseUrl}/trayecto/${trayectoId}/ubicacion`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ latitud: lat, longitud: lng }),
            })
                .then(res => {
                    if (!res.ok) throw new Error('Error al enviar ubicación');
                    estado.textContent = 'Compartiendo ubicación… última actualización: ' + new Date().toLocaleTimeString();
                })
                .catch(() => {
                    estado.textContent = 'No se pudo enviar la ubicación, reintentando…';
                });
        }

        function iniciarCompartir() {
            if (!navigator.geolocation) {
                estado.textContent = 'Este dispositivo no soporta geolocalización.';
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Compartiendo…';
            btn.classList.replace('bg-blue-600', 'bg-green-600');
            estado.textContent = 'Obteniendo ubicación…';

            watchId = navigator.geolocation.watchPosition(
                (posicion) => {
                    const ahora = Date.now();
                    if (ahora - ultimoEnvio >= INTERVALO_MINIMO_MS) {
                        ultimoEnvio = ahora;
                        enviarPosicion(posicion.coords.latitude, posicion.coords.longitude);
                    }
                },
                (error) => {
                    estado.textContent = 'No se pudo obtener tu ubicación: ' + error.message;
                },
                { enableHighAccuracy: true, maximumAge: 5000, timeout: 15000 }
            );
        }

        btn.addEventListener('click', iniciarCompartir);
    });
    </script>

</body>
</html>
