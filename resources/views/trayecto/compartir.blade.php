<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Compartir ubicación — Trayecto #{{ data_get($trayecto, 'id', '—') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-lg shadow p-6 w-full max-w-sm">
        <h1 class="text-xl font-semibold mb-1 text-center">Trayecto #{{ data_get($trayecto, 'id', '—') }}</h1>
        <p class="text-gray-500 mb-4 text-center">
            Pedido #{{ data_get($trayecto, 'pedido_id', '—') }} — {{ data_get($trayecto, 'estatus', '—') }}
        </p>

        @if ($yaTermino)
            <p class="text-sm text-gray-600 text-center">
                Este trayecto ya está <strong>{{ data_get($trayecto, 'estatus', '—') }}</strong> — ya no se puede compartir ubicación.
            </p>
        @else
            <div id="avisoInseguro" class="hidden bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4 text-xs text-amber-800">
                Tu navegador está bloqueando la ubicación porque esta página no se abrió por HTTPS (ni es
                "localhost"). Los celulares no comparten el GPS en conexiones sin HTTPS — abre este link
                desde una dirección segura (https://) para que funcione.
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4 text-xs text-gray-600 space-y-2">
                <p class="font-semibold text-gray-700">Aviso de privacidad simplificado</p>
                <p>
                    Zapatería Hermanos García recaba tu ubicación GPS en tiempo real únicamente mientras este
                    trayecto esté activo, con el fin de dar seguimiento a la entrega en curso y compartirla con
                    el personal autorizado de la matriz y la sucursal destino. No se comparte con terceros ajenos
                    a la empresa, y se deja de recabar en cuanto el trayecto se marca como entregado o cancelado.
                </p>
                <p>
                    Tienes derecho a acceder, rectificar, cancelar u oponerte (derechos ARCO) al uso de tu
                    ubicación, conforme a la Ley Federal de Protección de Datos Personales en Posesión de los
                    Particulares. Para ejercerlos, contacta a tu encargado de matriz.
                </p>
            </div>

            <label class="flex items-start gap-2 mb-4 text-sm text-gray-700">
                <input type="checkbox" id="aceptoAviso" class="mt-0.5">
                He leído y acepto el aviso de privacidad.
            </label>

            <button id="btnCompartir" disabled
                class="w-full bg-gray-300 text-white font-medium py-3 rounded-lg cursor-not-allowed transition-colors">
                Compartir mi ubicación
            </button>

            <p id="estadoTexto" class="text-sm text-gray-500 mt-4 text-center">
                Marca la casilla de arriba para poder compartir tu ubicación mientras haces la entrega.
            </p>
        @endif
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const urlUbicacion = @json($urlUbicacion ?? null);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const checkbox = document.getElementById('aceptoAviso');
        const btn = document.getElementById('btnCompartir');
        const estado = document.getElementById('estadoTexto');
        const avisoInseguro = document.getElementById('avisoInseguro');

        if (!btn) return;

        // Aviso si no hay HTTPS
        if (!window.isSecureContext) {
            avisoInseguro.classList.remove('hidden');
            checkbox.disabled = true;
            estado.textContent = 'No se puede compartir ubicación desde esta conexión (falta HTTPS).';
        }

        const INTERVALO_MINIMO_MS = 8000;
        let ultimoEnvio = 0;
        let watchId = null;

        checkbox.addEventListener('change', () => {
            btn.disabled = !checkbox.checked;
            btn.classList.toggle('bg-gray-300', !checkbox.checked);
            btn.classList.toggle('cursor-not-allowed', !checkbox.checked);
            btn.classList.toggle('bg-blue-600', checkbox.checked);
            btn.classList.toggle('hover:bg-blue-700', checkbox.checked);
            if (checkbox.checked) {
                estado.textContent = 'Toca el botón para empezar a enviar tu ubicación mientras haces la entrega.';
            }
        });

        function enviarPosicion(lat, lng) {
            if (!urlUbicacion) {
                estado.textContent = 'No se configuró la URL de envío.';
                return;
            }
            fetch(urlUbicacion, {
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
            if (!checkbox.checked) return;

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
                    if (error.code === error.PERMISSION_DENIED) {
                        estado.textContent = window.isSecureContext
                            ? 'Permiso de ubicación denegado. Revisa los permisos del sitio en tu navegador.'
                            : 'El navegador bloqueó el GPS porque esta conexión no es HTTPS.';
                    } else {
                        estado.textContent = 'No se pudo obtener tu ubicación: ' + error.message;
                    }
                },
                { enableHighAccuracy: true, maximumAge: 5000, timeout: 15000 }
            );
        }

        btn.addEventListener('click', iniciarCompartir);
    });
    </script>

</body>
</html>
