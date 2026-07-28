@extends('/plantilla/base')

@section('dinamico')

<h1 class="text-2xl font-semibold text-gray-800 mb-4">Nuevo pedido</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <form action="{{ url('/pedido/guardar') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        @csrf

        <div>
            <label for="empleado_id" class="block text-sm font-medium text-gray-700 mb-1">Empleado</label>
            <select name="empleado_id" id="empleado_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="">-- Selecciona --</option>
                @foreach ($empleados ?? [] as $empleado)
                    <option value="{{ $empleado->id }}">{{ $empleado->nombre }} {{ $empleado->apellido_paterno }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="estatus" class="block text-sm font-medium text-gray-700 mb-1">Estatus</label>
            <select name="estatus" id="estatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="Pendiente">Pendiente</option>
                <option value="Realizado">Realizado</option>
                <option value="Cancelado">Cancelado</option>
            </select>
        </div>

        <div class="sm:col-span-2">
            <label for="imagen" class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
            <input type="file" name="imagen" id="imagen" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required>
        </div>

        <div class="sm:col-span-2">
            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-lg text-sm hover:bg-blue-700">Guardar</button>
        </div>
    </form>
</div>

@endsection
