
@extends('/plantilla/base')

@section('dinamico')

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @if ($esMatrizOAdmin)
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Sucursales activas</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ $kpis['sucursales'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Pedidos pendientes</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ $kpis['pedidosPendientes'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Productos activos</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ $kpis['productos'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Choferes activos</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ $kpis['choferes'] }}</p>
        </div>
    @else
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Mis pedidos pendientes</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ $kpis['pendientes'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Mis pedidos en camino</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ $kpis['enCamino'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Mis pedidos entregados</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ $kpis['entregados'] }}</p>
        </div>
        <div class="bg-white p-5 rounded-lg shadow">
            <p class="text-sm text-gray-500">Productos activos</p>
            <p class="text-3xl font-semibold text-gray-900 mt-1">{{ $kpis['productos'] }}</p>
        </div>
    @endif
</div>

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
            @if ($esMatrizOAdmin)
                Top 5 por unidades pedidas en total.
            @else
                Top 5 que más ha pedido tu sucursal.
            @endif
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

    // Specs de la marca (no incluyen indexAxis: Chart.js solo lo lee en
    // options, nunca dentro del dataset).
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

    // ---- Pedidos por sucursal (matriz) / Mis pedidos por mes (sucursal) ----
    new Chart(document.getElementById('graficoSucursales'), {
        type: 'bar',
        data: {
            labels: @json($grafico1Labels),
            datasets: [{
                data: @json($grafico1Datos),
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

    // ---- Productos más solicitados (horizontal, nombres largos: aqua) ----
    new Chart(document.getElementById('graficoProductos'), {
        type: 'bar',
        data: {
            labels: @json($topProductos->pluck('nombre')),
            datasets: [{
                data: @json($topProductos->pluck('total')),
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

    // ---- Pedidos por estatus (horizontal, color fijo por estatus: bueno/alerta/crítico) ----
    const coloresEstatus = { Pendiente: '#fab219', Realizado: '#0ca30c', Cancelado: '#d03b3b' };
    const datosEstatus = @json($pedidosPorEstatus);

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
