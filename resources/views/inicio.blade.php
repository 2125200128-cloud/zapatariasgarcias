
@extends('/plantilla/base')

@section('dinamico')

         
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-semibold mb-4">Pedidos por mes</h2>
    <canvas id="graficoPedidos"></canvas>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById('graficoPedidos').getContext('2d');

    const labels = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'];
    const data = {
        labels: labels,
        datasets: [{
            label: 'Pedidos entregados',
            data: [12, 19, 3, 5, 2, 3],
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
        }]
    };

    const config = {
        type: 'bar',
        data: data,
        options: {
            animation: {
                delay: (context) => {
                    let delay = 0;
                    if (context.type === 'data' && context.mode === 'default') {
                        delay = context.dataIndex * 300 + context.datasetIndex * 100;
                    }
                    return delay;
                },
            },
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Pedidos entregados por mes'
                }
            }
        },
    };

    new Chart(ctx, config);
});
</script>

@endsection