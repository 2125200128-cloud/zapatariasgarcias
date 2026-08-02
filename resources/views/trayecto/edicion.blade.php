@extends('/plantilla/base')

@section('dinamico')

<h1 class="text-2xl font-semibold text-gray-800 mb-4">Editar trayecto #{{ $trayecto->id }}</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ url('/trayecto/actualizar/' . $trayecto->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @csrf

        <div class="sm:col-span-2">
            <label for="pedido_id" class="block text-sm font-medium text-gray-700 mb-1">Pedido</label>
            <select name="pedido_id" id="pedido_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @foreach ($pedidos as $pedido)
                    <option value="{{ $pedido->id }}" @selected($trayecto->pedido_id == $pedido->id)>
                        Pedido #{{ $pedido->id }} — {{ optional($pedido->empleado?->sucursales->first())->nombre ?? 'sin sucursal' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="chofer_id" class="block text-sm font-medium text-gray-700 mb-1">Chofer</label>
            <select name="chofer_id" id="chofer_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @foreach ($choferes as $chofer)
                    <option value="{{ $chofer->id }}" @selected($trayecto->chofer_id == $chofer->id)>{{ $chofer->nombre }} {{ $chofer->apellido }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="carro_id" class="block text-sm font-medium text-gray-700 mb-1">Carro</label>
            <select name="carro_id" id="carro_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                @foreach ($carros as $carro)
                    <option value="{{ $carro->id }}" @selected($trayecto->carro_id == $carro->id)>{{ $carro->placas }} — {{ $carro->marca }}</option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-2">
            <label for="estatus" class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" id="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                @foreach (['Pendiente', 'Aceptado', 'En ruta', 'Entregado', 'Cancelado'] as $estatus)
                    <option value="{{ $estatus }}" @selected($trayecto->estatus === $estatus)>{{ $estatus }}</option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-2">
            <label for="descripcion_ruta" class="block text-sm font-medium text-gray-700 mb-1">Descripción de la ruta</label>
            <input type="text" name="descripcion_ruta" id="descripcion_ruta" value="{{ $trayecto->descripcion_ruta }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="sm:col-span-2">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar cambios</button>
        </div>
    </form>
</div>

@endsection
