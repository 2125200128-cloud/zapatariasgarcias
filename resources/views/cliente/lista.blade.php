@extends('/plantilla/base')

@section('dinamico')

         
<h1>LISTA DE LOS CLIENTES</h1>
<div class="max-w-7xl mx-auto p-6">

    <div class="bg-white shadow-2xl rounded-2xl overflow-hidden border border-blue-100">

        <!-- Header -->
        <div class="bg-gray-700 text-white px-6 py-4">
            <h2 class="text-2xl font-bold">Tabla Clientes</h2>
            <p class="text-blue-100 text-sm">
                Información de clientes registrados
            </p>
        </div>

        <!-- Tabla -->
        <div class="overflow-x-auto">

            <table class="min-w-full text-sm text-left text-gray-700">

                <thead class="bg-gray-100 uppercase text-xs text-gray-600">
                    <tr>
                        <th class="px-6 py-4">Foto</th>
                        <th class="px-6 py-4">ID</th>
                        <th class="px-6 py-4">Nombre</th>
                        <th class="px-6 py-4">Apellidos</th>
                        <th class="px-6 py-4">Teléfono</th>
                        <th class="px-6 py-4">Correo</th>
                        {{-- <th class="px-6 py-4">Dirección</th> --}}
                        <th class="px-6 py-4">Estatus</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($clientes as $cliente)

<tr class="border-b hover:bg-gray-50 transition">

    <td class="px-6 py-4">
        <img src="{{ $cliente->imagen }}" alt="Foto de {{ $cliente->nombre }}" class="w-12 h-12 rounded-full object-cover">
    </td>

    <td class="px-6 py-4">{{ $cliente->id }}</td>

    <td class="px-6 py-4">{{ $cliente->nombre }}</td>

    <td class="px-6 py-4">
        {{ $cliente->apellido_paterno }}
        {{ $cliente->apellido_materno }}
    </td>

    <td class="px-6 py-4">{{ $cliente->telefono }}</td>

    <td class="px-6 py-4">{{ $cliente->correo }}</td>

    {{-- <td class="px-6 py-4">{{ $cliente->direccion }}</td> --}}

    <td class="px-6 py-4">{{ $cliente->estatus }}</td>

</tr>

@endforeach
    

                </tbody>

            </table>
            
            

        </div>

    </div>

</div>


@endsection