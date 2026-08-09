@extends('/plantilla/base')

@section('dinamico')

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @if ($esMatrizOAdmin)
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Sucursales activas</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ data_get($kpis, 'sucursales', 0) }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Pedidos pendientes</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ data_get($kpis, 'pedidosPendientes', 0) }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Productos activos</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ data_get($kpis, 'productos', 0) }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Choferes activos</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ data_get($kpis, 'choferes', 0) }}</p>
        </div>
    @else
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Mis pedidos pendientes</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ data_get($kpis, 'pendientes', 0) }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Mis pedidos en camino</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ data_get($kpis, 'enCamino', 0) }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Mis pedidos entregados</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ data_get($kpis, 'entregados', 0) }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Mi inventario (unidades)</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ data_get($kpis, 'inventarioTotal', 0) }}</p>
        </div>
    @endif
</div>

@if (!$esMatrizOAdmin && $trayectoActivoResumen)
    <div class="bg-white p-5 rounded-lg shadow mb-6 border-l-4 border-amber-400">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Entrega en curso — Pedido #{{ data_get($trayectoActivoResumen, 'pedido_id', '—') }}</p>
                <p class="text-lg font-semibold text-gray-900 mt-1">
                    {{ data_get($trayectoActivoResumen, 'estatus', '—') }}
                    @if (data_get($trayectoActivoResumen, 'chofer'))
                        — {{ data_get($trayectoActivoResumen, 'chofer') }}
                    @endif
                </p>
                @if (data_get($trayectoActivoResumen, 'descripcion_ruta'))
                    <p class="text-sm text-gray-500 mt-1">{{ data_get($trayectoActivoResumen, 'descripcion_ruta') }}</p>
                @endif
            </div>
            <a href="{{ url('/trayecto/flota') }}" class="bg-amber-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-amber-600 whitespace-nowrap">
                Ver en el mapa
            </a>
        </div>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow">
        @if ($esMatrizOAdmin)
            <h2 class="text-base font-semibold text-gray-800 mb-1">Pedidos por sucursal</h2>
            <p class="text-sm text-gray-500 mb-4">Volumen de pedidos que cada sucursal le ha solicitado a la matriz.</p>
        @else
            <h2 class="text-base font-semibold text-gray-800 mb-1">Mis pedidos por mes</h2>
            <p class="text-sm text-gray-500 mb-4">Cuántos pedidos ha hecho tu sucursal cada mes.</p>
        @endif
        <div class="relative h-64">
            <canvas id="graficoSucursales"></canvas>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-base font-semibold text-gray-800 mb-1">Productos más solicitados</h2>
        <p class="text-sm text-gray-500 mb-4">
            {{ $esMatrizOAdmin ? 'Top 5 por unidades pedidas en total.' : 'Top 5 que más ha pedido tu sucursal.' }}
        </p>
        <div class="relative h-64">
            <canvas id="graficoProductos"></canvas>
        </div>
    </div>
</div>

<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-base font-semibold text-gray-800 mb-1">
        {{ $esMatrizOAdmin ? 'Pedidos por estatus' : 'Mis pedidos por estatus' }}
    </h2>
    <p class="text-sm text-gray-500 mb-4">Qué tan avanzados van los pedidos activos.</p>
    <div class="relative h-48">
        <canvas id="graficoEstatus"></canvas>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const inkMuted = '#898781';
    const gridline = '#e1e0d9';

    Chart.defaults.font.family = "system-ui, -apple-system, 'Segoe UI', sans-serif";
    Chart.defaults.color = inkMuted;

    const marcaBarra = {
        maxBarThickness: 24,
        borderRadius: 4,
        borderSkipped: 'start',
    };

    const axisDefaults = {
        grid: { color: gridline, drawTicks: false },
        border: { display: false },
        ticks: { padding: 8 },
    };

    // ---- Pedidos por sucursal / Mis pedidos por mes ----
    new Chart(document.getElementById('graficoSucursales'), {
        type: 'bar',
        data: {
            labels: @json($grafico1Labels ?? []),
            datasets: [{
                data: @json($grafico1Datos ?? []),
                backgroundColor: '#2a78d6',
                ...marcaBarra,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { displayColors: false } },
            scales: {
                x: { ...axisDefaults, beginAtZero: true, ticks: { ...axisDefaults.ticks, precision: 0, stepSize: 1 } },
                y: { ...axisDefaults, grid: { display: false }, border: { display: true, color: '#c3c2b7' } },
            },
        },
    });

    // ---- Productos más solicitados ----
    new Chart(document.getElementById('graficoProductos'), {
        type: 'bar',
        data: {
            labels: @json($topProductos?->pluck('nombre') ?? []),
            datasets: [{
                data: @json($topProductos?->pluck('total') ?? []),
                backgroundColor: '#1baf7a',
                ...marcaBarra,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { displayColors: false } },
            scales: {
                x: { ...axisDefaults, beginAtZero: true, ticks: { ...axisDefaults.ticks, precision: 0 } },
                y: { ...axisDefaults, grid: { display: false }, border: { display: true, color: '#c3c2b7' } },
            },
        },
    });

    // ---- Pedidos por estatus ----
    const coloresEstatus = { Pendiente: '#fab219', Realizado: '#0ca30c', Cancelado: '#d03b3b' };
    const datosEstatus = @json($pedidosPorEstatus ?? []);

    new Chart(document.getElementById('graficoEstatus'), {
        type: 'bar',
        data: {
            labels: datosEstatus.map(d => d.estatus),
            datasets: [{
                data: datosEstatus.map(d => d.total),
                backgroundColor: datosEstatus.map(d => coloresEstatus[d.estatus] || '#898781'),
                ...marcaBarra,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { displayColors: false } },
            scales: {
                x: { ...axisDefaults, beginAtZero: true, ticks: { ...axisDefaults.ticks, precision: 0 } },
                y: { ...axisDefaults, grid: { display: false }, border: { display: true, color: '#c3c2b7' } },
            },
        },
    });
});
</script>

@endsection