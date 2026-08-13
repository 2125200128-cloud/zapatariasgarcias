@extends('/plantilla/base')

@section('dinamico')

{{-- Título de página / Imagen y Nombre --}}
<div class="flex flex-wrap items-center justify-between gap-6 mb-10 my-12 mx-85 px-4">
    <div class="flex items-center gap-4 " class="shrink-0 transition-transform hover:scale-105">
        <img src="{{ asset('images/politica.png') }}" alt="Politica-de-Privacidad" class="w-26 h-26 object-contain ">
        <div>
            <h1 class="text-4xl font-serif text-brand-dark px-4">Política de privacidad</h1>
            <h3 class="font-serif text-[#715b49] px-4">Actualizada el 01 de agosto de 2026</h3>
        </div>    
    </div>
    
    <div>
        <p class="text-[#3d3228] font-serif text-xs">
            En la Política de privacidad de Redivo, se describe la manera en que recopila, utiliza y comparte sus datos personales. 
            Además de esta Política de privacidad, proporcionamos datos e información sobre la privacidad incluidos en nuestros 
            servicios y determinadas funcionalidades que solicitan utilizar tus datos personales. Esta información específica de los 
            servicios lleva nuestro ícono de Datos y privacidad.
        </p>
    </div>

    <div>
        <h4 class="font-serif text-brand-black-coffe">Responsable del tratamiento de datos personales</h4>
        <p class="text-[#3d3228] font-serif text-xs">La empresa Zapatería García y Hermanos, con domicilio en (dirección), es responsable del uso y 
            protección de los datos personales que nos proporcione.</p>
    </div>

    <div>
        <h4 class="font-serif text-brand-black-coffe">Datos personales que se recaban</h4>
        <p class="text-[#3d3228] font-serif text-xs">Para la correcta operación del sistema de información y la prestación de nuestros servicios, 
            se podrán solicitar los siguientes datos:
            <ul>
                <li class="text-[#3d3228] font-serif text-xs">Nombre completo</li>
                <li class="text-[#3d3228] font-serif text-xs">Teléfono y correo electrónico</li>
                <li class="text-[#3d3228] font-serif text-xs">Dirección de entrega</li>
                <li class="text-[#3d3228] font-serif text-xs">Información de pedidos y compras realizadas</li>
                <li class="text-[#3d3228] font-serif text-xs">Datos de proveedores (razón social, contacto, condiciones de servicio)</li>
            </ul>
        </p>
        <br>
        <p class="text-[#3d3228] font-serif text-xs">No se solicitarán datos sensibles como información financiera, estado de salud o preferencias personales.</p>
    </div>

    <div>
        <h4 class="font-serif text-brand-black-coffe">Finalidades del tratamiento</h4>
        <p class="text-[#3d3228] font-serif text-xs">Los datos personales recabados serán utilizados para:
            <ul>
                <li class="text-[#3d3228] font-serif text-xs">Registrar y administrar productos y pedidos.</li>
                <li class="text-[#3d3228] font-serif text-xs">Controlar inventarios en sucursales y matriz.</li>
                <li class="text-[#3d3228] font-serif text-xs">Gestionar proveedores y marcas.</li>
                <li class="text-[#3d3228] font-serif text-xs">Emitir reportes de ventas e inventarios.</li>
                <li class="text-[#3d3228] font-serif text-xs">Mantener comunicación con clientes y proveedores.</li>
            </ul>
        </p>
    </div>

    <div>
        <h4 class="font-serif text-brand-black-coffe">Transferencia de datos</h4>
        <p class="text-[#3d3228] font-serif text-xs">Sus datos personales no serán compartidos con terceros ajenos a la empresa, 
            salvo en los siguientes casos:</p>
            <ul>
                <li class="text-[#3d3228] font-serif text-xs">Autoridades competentes que lo soliciten conforme a la ley.</li>
                <li class="text-[#3d3228] font-serif text-xs">Proveedores de servicios tecnológicos contratados para el funcionamiento 
                    del sistema (ejemplo: hosting, respaldo digital).</li>
            </ul>
    </div>

    <div>
        <h4 class="font-serif text-brand-black-coffe">Medidas de seguridad</h4>
        <p class="text-[#3d3228] font-serif text-xs">El sistema implementa controles de acceso mediante usuarios y contraseñas,
            respaldo de información y protección contra pérdida, alteración o acceso no autorizado.</p>
    </div>

    <div>
        <h4 class="font-serif text-brand-black-coffe">Derechos ARCO (Acceso, Rectificación, Cancelación y Oposición)</h4>
        <p class="text-[#3d3228] font-serif text-xs">Usted tiene derecho a acceder, rectificar, cancelar u oponerse al uso de sus 
            datos personales. Para ejercer estos derechos, puede enviar una solicitud al correo electrónico: privacidad@zapateriagarcia.com.</p>
    </div>

    <div>
        <h4 class="font-serif text-brand-black-coffe">Cambios al aviso de privacidad</h4>
        <p class="text-[#3d3228] font-serif text-xs">Este aviso podrá ser modificado en cualquier momento para cumplir con actualizaciones 
            legales o mejoras en el sistema. Las modificaciones estarán disponibles en nuestro sitio web y repositorio digital.</p>
    </div>

</div>
@endsection