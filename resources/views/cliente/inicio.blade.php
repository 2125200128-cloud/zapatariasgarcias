@extends('/plantilla/base')

@section('dinamico')

         
<h1>MODULO DE LOS CLIENTES</h1>

 <a href="/cliente/formulario"
        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-8 py-3 rounded-xl shadow-lg transition duration-300 inline-block mt-6">
        Agregar Cliente
    </a>
    <a href="/cliente/lista"
        class="bg-blue-800 hover:bg-blue-900 text-white font-semibold px-8 py-3 rounded-xl shadow-lg transition duration-300 inline-block mt-6">
        Ver Clientes
    </a>
    <a href="/cliente/edicion/1"
        class="bg-blue-800 hover:bg-blue-900 text-white font-semibold px-8 py-3 rounded-xl shadow-lg transition duration-300 inline-block mt-6">
        Editar Cliente
    </a>
    <a href="/cliente/borrado/1"
        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-8 py-3 rounded-xl shadow-lg transition duration-300 inline-block mt-6">
        Eliminar Cliente
    </a>

@endsection