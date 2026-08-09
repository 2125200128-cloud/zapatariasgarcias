@extends('/plantilla/base')

@section('dinamico')

<h1 class="text-2xl font-semibold mb-4">Mapa de flota — Trayectos activos</h1>

<div class="bg-white p-4 rounded-lg shadow mb-4">
    <div class="flex flex-wrap gap-4 text-sm mb-3">
        <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full" style="background:#6b7280"></span> Pendiente</span>
        <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full" style="background:#3b82f6"></span> Aceptado</span>
        <span class="flex items-center gap-1"><span class="inline-block w-3 h-3 rounded-full" style="background:#f59e0b"></span> En ruta</span>
    </div>
    <div id="mapaFlota" style="height: 600px; border-radius: 0.5rem;"></div>
    <p id="mapaFlotaVacio" class="text-gray-500 text-sm mt-3 hidden">No hay trayectos activos en este momento.</p>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const colores = {
        'Pendiente': '#6b7280',
        'Aceptado': '#3b82f6',
        'En ruta': '#f59e0b',
    };

    const matriz = @json(config('ubicaciones.matriz'));

    const mapa = L.map('mapaFlota').setView([20.9, -103.5], 7);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(mapa);

    function iconoPunto(color, emoji) {
        return L.divIcon({
            html: `<div style="background:${color};width:26px;height:26px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid white;box-shadow:0 1px 4px rgba(0,0,0,.4);font-size:14px;">${emoji}</div>`,
            className: '',
            iconSize: [26, 26],
            iconAnchor: [13, 13],
        });
    }

    L.marker([matriz.lat, matriz.lng], { icon: iconoPunto('#111827', '🏢') })
        .addTo(mapa)
        .bindPopup(`<strong>${matriz.nombre}</strong><br>Origen de los pedidos`);

    const marcadores = {}; // trayecto_id -> { chofer, destino, linea }
    const rutasCache = {}; // municipio -> [[lat,lng], ...] | 'error'

    function obtenerRutaCarretera(destino, callback) {
        if (rutasCache[destino.municipio]) {
            callback(rutasCache[destino.municipio] === 'error' ? null : rutasCache[destino.municipio]);
            return;
        }

        const url = `https://router.project-osrm.org/route/v1/driving/${matriz.lng},${matriz.lat};${destino.lng},${destino.lat}?overview=full&geometries=geojson`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (data.code !== 'Ok' || !data.routes?.[0]) {
                    throw new Error('OSRM sin ruta');
                }
                const puntos = data.routes[0].geometry.coordinates.map(([lng, lat]) => [lat, lng]);
                rutasCache[destino.municipio] = puntos;
                callback(puntos);
            })
            .catch(() => {
                rutasCache[destino.municipio] = 'error';
                callback(null);
            });
    }

    function actualizar() {
        fetch('{{ url('/trayecto/flota/ubicaciones')}}', { headers: { 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(trayectos => {
                document.getElementById('mapaFlotaVacio').classList.toggle('hidden', trayectos.length > 0);

                const activos = new Set(trayectos.map(t => t.trayecto_id));

                // Quitar marcadores de trayectos que ya no están activos
                Object.keys(marcadores).forEach(id => {
                    if (!activos.has(Number(id))) {
                        const grupo = marcadores[id];
                        if (grupo.chofer) mapa.removeLayer(grupo.chofer);
                        if (grupo.destino) mapa.removeLayer(grupo.destino);
                        if (grupo.linea) mapa.removeLayer(grupo.linea);
                        delete marcadores[id];
                    }
                });

                trayectos.forEach(t => {
                    if (!marcadores[t.trayecto_id]) {
                        marcadores[t.trayecto_id] = {};
                    }
                    const grupo = marcadores[t.trayecto_id];
                    const color = colores[t.estatus] || '#6b7280';

                    // Pin de destino (sucursal) + línea matriz -> destino
                    if (t.destino && !grupo.destino) {
                        grupo.destino = L.marker([t.destino.lat, t.destino.lng], { icon: iconoPunto('#065f46', '🏬') })
                            .addTo(mapa)
                            .bindPopup(`<strong>${t.destino.sucursal}</strong><br>${t.destino.municipio}`);

                        grupo.linea = L.polyline(
                            [[matriz.lat, matriz.lng], [t.destino.lat, t.destino.lng]],
                            { color: '#9ca3af', dashArray: '6 6', weight: 2 }
                        ).addTo(mapa);

                        obtenerRutaCarretera(t.destino, (puntos) => {
                            if (puntos && grupo.linea) {
                                grupo.linea.setLatLngs(puntos);
                                grupo.linea.setStyle({ dashArray: null, color: '#6366f1' });
                            }
                        });
                    }

                    // Posición del chofer (si ya comparte GPS)
                    if (t.posicion) {
                        const popup = `<strong>${t.chofer ?? 'Chofer'}</strong><br>Pedido #${t.pedido_id}<br>Estatus: ${t.estatus}<br><small>Actualizado: ${t.posicion.actualizado_en}</small>`;
                        if (grupo.chofer) {
                            grupo.chofer.setLatLng([t.posicion.lat, t.posicion.lng]);
                            grupo.chofer.setPopupContent(popup);
                        } else {
                            grupo.chofer = L.marker([t.posicion.lat, t.posicion.lng], { icon: iconoPunto(color, '🚚') })
                                .addTo(mapa)
                                .bindPopup(popup);
                        }
                    }
                });
            })
            .catch(err => console.error('No se pudo actualizar el mapa de flota:', err));
    }

    actualizar();
    setInterval(actualizar, 7000);
});
</script>

@endsection
