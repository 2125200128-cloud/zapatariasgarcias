@extends('/plantilla/base')

@section('dinamico')

<div class="relative rounded-lg shadow m-2 overflow-hidden h-75 bg-cover bg-center bg-no-repeat"
     style="background-image: url('{{ asset('images/zapatera.png') }}')">

    <div class="relative h-full flex items-center px-10">
        <div>
            <h2 class="text-3xl font-serif text-[#17181d]">Bienvenido a</h2>
            <h2 class="text-6xl font-serif text-[#17181d]">Hermanos García</h2>
            <p class="text-2xl font-serif text-[#af7442] mt-4">Dejando huellas juntos</p>
            <p class="text-1xl font-serif text-[#17181d] mt-4">Controla y supervisa las operaciones de tu negocio en tiepo real</p>
        </div>
    </div>
</div>

<div class="text-2xl font-serif mt-8 p-4 text-[#88520f]">
    <h4>Resumen del Día</h4>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    @if ($esMatrizOAdmin)
        <div class="bg-brand-brown/90 p-5 rounded-lg shadow m-1 border border-[#af7442] flex items-center gap-3">
            <a href="{{ url('/sucursal') }}">
                <img src="{{ asset('images/sucursal.png') }}" alt="Sucursales" class="w-13 h-13 m-2">
            </a>
            <div>
                <p class="text-sm text-[#381e0a] font-serif">Sucursales activas</p>
                <p class="text-3xl font-semibold text-[#17181d] mt-1">{{ data_get($kpis, 'sucursales', 0) }}</p>
            </div>
        </div>

        <div class="bg-brand-brown/90 p-5 rounded-lg shadow m-1 border border-[#af7442] flex items-center gap-3">
            <a href="{{ url('/pedido/pendientes') }}">
                <img src="{{ asset('images/pedidos.png') }}" alt="Pedidos" class="w-13 h-13 m-2">    
            </a>
            <div>
                <p class="text-sm text-[#381e0a] font-serif">Pedidos pendientes</p>
                <p class="text-3xl font-semibold text-[#17181d] mt-1">{{ data_get($kpis, 'pedidosPendientes', 0) }}</p>
            </div>
        </div>

        <div class="bg-brand-brown/90 p-5 rounded-lg shadow m-1 border border-[#af7442] flex items-center gap-3">
            <a href="{{ url('/producto') }}">
                <img src="{{ asset('images/products.png') }}" alt="Productos" class="w-13 h-13 m-2">
            </a>
            <div>
                <p class="text-sm text-[#381e0a] font-serif">Productos activos</p>
                <p class="text-3xl font-semibold text-[#17181d] mt-1">{{ data_get($kpis, 'productos', 0) }}</p>
            </div>
        </div>

        <div class="bg-brand-brown/90 p-5 rounded-lg shadow m-1 border border-[#af7442] flex items-center gap-3">
            <a href="{{ url('/chofer') }}">
                <img src="{{ asset('images/choferes.png') }}" alt="Choferes" class="w-14 h-14 m-2">
            </a>    
            <div>
                <p class="text-sm text-[#381e0a] font-serif">Choferes activos</p>
                <p class="text-3xl font-semibold text-[#17181d] mt-1">{{ data_get($kpis, 'choferes', 0) }}</p>
            </div>
        </div>
    @else
        <div class="bg-white p-5 rounded-lg shadow m-1 border border-[#af7442]">
            <p class="text-sm text-[#381e0a] font-serif">Mis pedidos pendientes</p>
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

<div class="text-2xl font-serif mt-8 p-4 text-[#88520f]">
    Actividades de sucursales
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-brand-brown/80 p-6 rounded-lg shadow">
        @if ($esMatrizOAdmin)
            <h2 class="text-base font-semibold text-[#362005] mb-1">Pedidos por sucursal</h2>
            <p class="text-sm text-[#302e2c] mb-4">Volumen de pedidos que cada sucursal le ha solicitado a la matriz.</p>
        @else
            <h2 class="text-base font-semibold text-[#362005] mb-1">Mis pedidos por mes</h2>
            <p class="text-sm text-[#302e2c] mb-4">Cuántos pedidos ha hecho tu sucursal cada mes.</p>
        @endif
        <div class="relative h-64">
            <canvas id="graficoSucursales"></canvas>
        </div>
    </div>
    
    <div class="bg-brand-brown/80 p-6 rounded-lg shadow">
        <h2 class="text-base font-semibold text-[#362005] mb-1">Productos más solicitados</h2>
        <p class="text-sm mb-4 text-[#302e2c]">
            {{ $esMatrizOAdmin ? 'Top 5 por unidades pedidas en total.' : 'Top 5 que más ha pedido tu sucursal.' }}
        </p>
        <div class="relative h-64">
            <canvas id="graficoProductos"></canvas>
        </div>
    </div>
</div>

<div class="text-2xl font-semibold font-brand mt-1 p-4 text-[#c0891c]">
    Resumen de los Pedidos
</div>

<div class="bg-brand-brown/90 p-6 rounded-lg shadow">
    <h2 class="text-base font-semibold text-[#362005] mb-1">
        {{ $esMatrizOAdmin ? 'Pedidos por estatus' : 'Mis pedidos por estatus' }}
    </h2>
    <p class="text-sm mb-4 text-[#302e2c]">Avance de pedidos activos.</p>
    <div class="relative h-48">
        <canvas id="graficoEstatus"></canvas>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const inkMuted = '#2b2723';
    const gridline = '#2b2723';

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
                backgroundColor: '#675647',
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
                backgroundColor: '#b4977d',
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
                y: { ...axisDefaults, grid: { display: false }, border: { display: true, color: '#2b2723' } },
            },
        },
    });

    // ---- Pedidos por estatus ----
    const coloresEstatus = { Pendiente: '#CC6B22', Realizado: '#4B633B', Cancelado: '#80453E' };
    const datosEstatus = @json($pedidosPorEstatus ?? []);

    new Chart(document.getElementById('graficoEstatus'), {
        type: 'bar',
        data: {
            labels: datosEstatus.map(d => d.estatus),
            datasets: [{
                data: datosEstatus.map(d => d.total),
                backgroundColor: datosEstatus.map(d => coloresEstatus[d.estatus] || '#362005'),
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
                y: { ...axisDefaults, grid: { display: false }, border: { display: true, color: '#BA9D8A' } },
            },
        },
    });
});
</script>

@endsection