
@extends('/plantilla/base')

@section('dinamico')

         
<h1>INICIO</h1>
{{-- <canvas id="grafica"></canvas>

<script>

const ctx = document.getElementById('grafica');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Empleados', 'Clientes', 'Productos', 'Pedidos'],
        datasets: [{
            label: 'Registros',
            data: [
                {{ $empleados }},
                {{ $clientes }},
                {{ $productos }},
                {{ $pedidos }}
            ]
        }]
    }
});
</script> --}}
@endsection