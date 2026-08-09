@extends('/plantilla/base')

@section('dinamico')

<div class="max-w-3xl rounded-lg bg-white p-6 shadow">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Editar trayecto #{{ data_get($trayecto, 'id', '—') }}</h1>
            <p class="text-sm text-gray-500">Actualiza la información del trayecto en la API.</p>
        </div>
        <a href="{{ url('/trayecto') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Volver</a>
    </div>

    @if ($errors->any())
        <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-sm text-red-700">
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('/trayecto/actualizar/' . data_get($trayecto, 'id', '')) }}" method="POST" class="grid gap-4 sm:grid-cols-2">
        @csrf

        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Pedido</label>
            <select name="pedido_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                @foreach ($pedidos ?? [] as $pedido)
                    <option value="{{ data_get($pedido, 'id', '') }}" 
                        {{ old('pedido_id', data_get($trayecto, 'pedido_id', '')) == data_get($pedido, 'id', '') ? 'selected' : '' }}>
                        Pedido #{{ data_get($pedido, 'id', '—') }} — {{ data_get($pedido, 'sucursal.nombre', 'sin sucursal') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Chofer</label>
            <select name="chofer_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                @foreach ($choferes ?? [] as $chofer)
                    <option value="{{ data_get($chofer, 'id', '') }}" 
                        {{ old('chofer_id', data_get($trayecto, 'chofer_id', '')) == data_get($chofer, 'id', '') ? 'selected' : '' }}>
                        {{ data_get($chofer, 'nombre', 'Chofer') }} {{ data_get($chofer, 'apellido', '') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Carro</label>
            <select name="carro_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
                @foreach ($carros ?? [] as $carro)
                    <option value="{{ data_get($carro, 'id', '') }}" 
                        {{ old('carro_id', data_get($trayecto, 'carro_id', '')) == data_get($carro, 'id', '') ? 'selected' : '' }}>
                        {{ data_get($carro, 'placas', '—') }} — {{ data_get($carro, 'marca', '—') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                @foreach (['Pendiente', 'Aceptado', 'En ruta', 'Entregado', 'Cancelado'] as $estatus)
                    <option value="{{ $estatus }}" {{ old('estatus', data_get($trayecto, 'estatus', '')) === $estatus ? 'selected' : '' }}>
                        {{ $estatus }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción de la ruta</label>
            <input type="text" name="descripcion_ruta" value="{{ old('descripcion_ruta', data_get($trayecto, 'descripcion_ruta', '')) }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div class="sm:col-span-2 flex justify-end gap-3">
            <a href="{{ url('/trayecto') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">Cancelar</a>
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar cambios</button>
        </div>
    </form>
</div>

@endsection
