@extends('/plantilla/base')

@section('dinamico')

<h1 class="text-2xl font-semibold text-gray-800 mb-4">Aceptar pedido #{{ $pedido->id }}</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-2xl">
    @if (session('error'))
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <p class="text-sm text-gray-500 mb-1">Sucursal que solicita</p>
    <p class="text-sm font-medium text-gray-800 mb-4">{{ optional($pedido->empleado?->sucursales->first())->nombre ?? '—' }}</p>

    <p class="text-sm text-gray-500 mb-2">Productos solicitados</p>
    <table class="w-full text-sm mb-6 border border-gray-200 rounded-lg overflow-hidden">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="text-left px-3 py-2">Producto</th>
                <th class="text-left px-3 py-2">Talla</th>
                <th class="text-left px-3 py-2">Cantidad</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($pedido->detallePedidos as $detalle)
                <tr>
                    <td class="px-3 py-2">{{ $detalle->producto->nombre ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $detalle->producto->talla ?? '—' }}</td>
                    <td class="px-3 py-2">{{ $detalle->cantidad_solicitada }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <form action="{{ url('/pedido/' . $pedido->id . '/aceptar') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div>
                <label for="chofer_id" class="block text-sm font-medium text-gray-700 mb-1">Chofer</label>
                <select name="chofer_id" id="chofer_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Selecciona --</option>
                    @foreach ($choferes ?? [] as $chofer)
                        <option value="{{ $chofer->id }}">{{ $chofer->nombre }} {{ $chofer->apellido }}</option>
                    @endforeach
                </select>
                @if (isset($choferes) && $choferes->isEmpty())
                    <p class="text-xs text-amber-600 mt-1">No hay choferes disponibles ahora mismo.</p>
                @endif
            </div>

            <div>
                <label for="carro_id" class="block text-sm font-medium text-gray-700 mb-1">Carro</label>
                <select name="carro_id" id="carro_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Selecciona --</option>
                    @foreach ($carros ?? [] as $carro)
                        <option value="{{ $carro->id }}">{{ $carro->placas }} — {{ $carro->marca }}</option>
                    @endforeach
                </select>
                @if (isset($carros) && $carros->isEmpty())
                    <p class="text-xs text-amber-600 mt-1">No hay carros disponibles ahora mismo.</p>
                @endif
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">
                Aceptar y asignar entrega
            </button>
            <a href="{{ url('/pedido/pendientes') }}" class="px-5 py-2 rounded-lg text-sm border border-gray-300 hover:bg-gray-50">Cancelar</a>
        </div>
    </form>
</div>

@endsection
