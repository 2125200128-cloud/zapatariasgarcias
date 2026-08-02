<?php

namespace Database\Seeders;

use App\Models\Carro;
use App\Models\Chofer;
use App\Models\Detalle_pedido;
use App\Models\Empleado;
use App\Models\Marca;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Sucursal;
use App\Models\Trayecto;
use App\Models\TrayectoUbicacion;
use Illuminate\Database\Seeder;

// Datos de ejemplo coherentes con el caso de estudio "GARCIA Y HERMANOS":
// matriz en Guadalajara distribuyendo calzado a sus 5 sucursales de Jalisco
// (Zapopan, Ciudad Guzmán, Puerto Vallarta, Lagos de Moreno, San Juan de los
// Lagos), cada una con su pedido y su trayecto de entrega en distinto estatus.
// No se ejecuta automáticamente con `db:seed`; correr con:
//   php artisan db:seed --class=TrayectoDemoSeeder
class TrayectoDemoSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Proveedores y marcas de calzado ----
        $proveedorAndrea = Proveedor::create([
            'nombre' => 'Calzado Andrea S.A. de C.V.',
            'contacto' => '3312223344',
            'correo' => 'ventas@calzadoandrea.com.mx',
            'calle' => 'Av. Patria',
            'numero' => 2200,
            'municipio' => 'Zapopan',
            'codigo_postal' => '45030',
            'estatus' => 'Activo',
            'imagen' => 'demo/proveedor_andrea.jpg',
        ]);

        $proveedorFlexi = Proveedor::create([
            'nombre' => 'Grupo Flexi de Occidente',
            'contacto' => '4771122334',
            'correo' => 'pedidos@flexi.com.mx',
            'calle' => 'Blvd. Adolfo López Mateos',
            'numero' => 1500,
            'municipio' => 'León',
            'codigo_postal' => '37000',
            'estatus' => 'Activo',
            'imagen' => 'demo/proveedor_flexi.jpg',
        ]);

        $marcaAndrea = Marca::create([
            'proveedor_id' => $proveedorAndrea->id,
            'nombre' => 'Andrea',
            'imagen' => 'demo/marca_andrea.jpg',
        ]);

        $marcaFlexi = Marca::create([
            'proveedor_id' => $proveedorFlexi->id,
            'nombre' => 'Flexi',
            'imagen' => 'demo/marca_flexi.jpg',
        ]);

        // ---- Productos ----
        $productos = [
            Producto::create([
                'marca_id' => $marcaAndrea->id,
                'proveedor_id' => $proveedorAndrea->id,
                'nombre' => 'Tenis Andrea Urban',
                'descripcion' => 'Tenis casual para caballero, suela flexible',
                'precio' => 899.00,
                'modelo' => 'AN-2024',
                'color' => 'Negro',
                'sexo' => 'Masculino',
                'categoria' => 'Tenis',
                'talla' => '27',
                'estatus' => 'Activo',
                'imagen1' => 'demo/producto_tenis_andrea.jpg',
            ]),
            Producto::create([
                'marca_id' => $marcaFlexi->id,
                'proveedor_id' => $proveedorFlexi->id,
                'nombre' => 'Zapato Flexi Confort',
                'descripcion' => 'Zapato de vestir con plantilla acolchada',
                'precio' => 1099.00,
                'modelo' => 'FX-310',
                'color' => 'Café',
                'sexo' => 'Masculino',
                'categoria' => 'Casual',
                'talla' => '26',
                'estatus' => 'Activo',
                'imagen1' => 'demo/producto_zapato_flexi.jpg',
            ]),
            Producto::create([
                'marca_id' => $marcaAndrea->id,
                'proveedor_id' => $proveedorAndrea->id,
                'nombre' => 'Sandalia Andrea Verano',
                'descripcion' => 'Sandalia dama con hebilla ajustable',
                'precio' => 599.00,
                'modelo' => 'AN-SV12',
                'color' => 'Blanco',
                'sexo' => 'Femenino',
                'categoria' => 'Sandalias',
                'talla' => '24',
                'estatus' => 'Activo',
                'imagen1' => 'demo/producto_sandalia_andrea.jpg',
            ]),
        ];

        // ---- Sucursales (las 5 del caso de estudio) + su empleado encargado ----
        $sucursalesData = [
            ['municipio' => 'Zapopan', 'slug' => 'zapopan', 'cp' => '45100', 'lada' => '33', 'nombre' => 'María Fernanda Torres Gutiérrez'],
            ['municipio' => 'Ciudad Guzmán', 'slug' => 'ciudadguzman', 'cp' => '49000', 'lada' => '341', 'nombre' => 'Luis Ángel Ramírez Soto'],
            ['municipio' => 'Puerto Vallarta', 'slug' => 'puertovallarta', 'cp' => '48300', 'lada' => '322', 'nombre' => 'Karla Patricia Núñez Reyes'],
            ['municipio' => 'Lagos de Moreno', 'slug' => 'lagosdemoreno', 'cp' => '47400', 'lada' => '474', 'nombre' => 'José Manuel Flores Aceves'],
            ['municipio' => 'San Juan de los Lagos', 'slug' => 'sanjuandeloslagos', 'cp' => '47000', 'lada' => '395', 'nombre' => 'Ana Sofía Delgado Vázquez'],
        ];

        $sucursales = [];
        $contador = 1;
        foreach ($sucursalesData as $datos) {
            // Los nombres tienen 2 palabras de nombre(s) + apellido paterno +
            // apellido materno; se toman las últimas 2 palabras como apellidos
            // y el resto como nombre (evita cortar mal nombres compuestos).
            $partes = explode(' ', $datos['nombre']);
            $apellidoMaterno = array_pop($partes);
            $apellidoPaterno = array_pop($partes);
            $nombre = implode(' ', $partes);

            $empleado = Empleado::create([
                'nombre' => $nombre,
                'apellido_paterno' => $apellidoPaterno,
                'apellido_materno' => $apellidoMaterno,
                'correo' => 'encargado.' . $datos['slug'] . '@zapateriagarcias.com.mx',
                'telefono' => str_pad($datos['lada'] . $contador, 10, '0', STR_PAD_RIGHT),
                'contrasena' => bcrypt('demo1234'),
                'usuario' => 'encargado.' . $datos['slug'],
                'rol' => 'Encargado',
                'estatus' => 'Activo',
                'calle' => 'Av. Principal',
                'numero' => 100 * $contador,
                'municipio' => $datos['municipio'],
                'codigo_postal' => $datos['cp'],
                'imagen' => 'demo/empleado_' . $contador . '.jpg',
            ]);

            $sucursal = Sucursal::create([
                'empleado_id' => $empleado->id,
                'nombre' => 'Sucursal ' . $datos['municipio'],
                'calle' => 'Av. Principal',
                'numero' => 100 * $contador,
                'municipio' => $datos['municipio'],
                'codigo_postal' => $datos['cp'],
                'contacto' => str_pad($datos['lada'] . $contador, 10, '0', STR_PAD_RIGHT),
                'imagen' => 'demo/sucursal_' . $contador . '.jpg',
                'estatus' => 'Activo',
            ]);

            $sucursales[$datos['municipio']] = $sucursal;
            $contador++;
        }

        // ---- Choferes y carros de reparto ----
        $choferes = [
            Chofer::create(['nombre' => 'Roberto Carlos', 'apellido' => 'Jiménez Palomera', 'contacto' => '3311122233', 'imagen' => 'demo/chofer_1.jpg', 'estatus' => 'Activo']),
            Chofer::create(['nombre' => 'Miguel Ángel', 'apellido' => 'Hernández Ruiz', 'contacto' => '3311122244', 'imagen' => 'demo/chofer_2.jpg', 'estatus' => 'Activo']),
            Chofer::create(['nombre' => 'Francisco Javier', 'apellido' => 'Torres Medina', 'contacto' => '3311122255', 'imagen' => 'demo/chofer_3.jpg', 'estatus' => 'Activo']),
        ];

        $carros = [
            Carro::create(['placas' => 'JBA-2201', 'marca' => 'Nissan NP300', 'color' => 'Blanco', 'capacidad' => 800, 'imagen' => 'demo/carro_1.jpg', 'estatus' => 'Ocupado']),
            Carro::create(['placas' => 'JBB-3352', 'marca' => 'Chevrolet Tornado', 'color' => 'Gris', 'capacidad' => 650, 'imagen' => 'demo/carro_2.jpg', 'estatus' => 'Ocupado']),
            Carro::create(['placas' => 'JBC-4103', 'marca' => 'Ford Transit', 'color' => 'Blanco', 'capacidad' => 1000, 'imagen' => 'demo/carro_3.jpg', 'estatus' => 'Disponible']),
        ];

        // ---- Pedidos (uno por sucursal, con su detalle) ----
        $pedidos = [];
        foreach ($sucursales as $municipio => $sucursal) {
            $pedido = Pedido::create([
                'empleado_id' => $sucursal->empleado_id,
                'estatus' => 'Realizado',
            ]);

            foreach (array_slice($productos, 0, 2) as $producto) {
                Detalle_pedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $producto->id,
                    'precio' => $producto->precio,
                    'cantidad_solicitada' => random_int(6, 20),
                ]);
            }

            $pedidos[$municipio] = $pedido;
        }

        // ---- Trayectos: distintos estatus para ver el mapa de flota variado ----
        // Solo hay 3 choferes/carros, así que como mucho 3 trayectos pueden
        // estar "activos" (Pendiente/Aceptado/En ruta) al mismo tiempo sin
        // que se repita chofer o carro — un mismo chofer/carro sí puede
        // repetirse, pero solo en trayectos ya terminados (Entregado /
        // Cancelado), nunca en dos activos a la vez.
        $trayectosData = [
            ['municipio' => 'Zapopan', 'chofer' => 0, 'carro' => 0, 'estatus' => 'En ruta'],
            ['municipio' => 'Ciudad Guzmán', 'chofer' => 1, 'carro' => 1, 'estatus' => 'En ruta'],
            ['municipio' => 'Puerto Vallarta', 'chofer' => 2, 'carro' => 2, 'estatus' => 'Aceptado'],
            // Viajes ya terminados que reutilizan chofer/carro de arriba —
            // válido porque no coinciden en el tiempo con los activos.
            ['municipio' => 'Lagos de Moreno', 'chofer' => 0, 'carro' => 0, 'estatus' => 'Entregado'],
            ['municipio' => 'San Juan de los Lagos', 'chofer' => 1, 'carro' => 1, 'estatus' => 'Cancelado'],
        ];

        $matriz = config('ubicaciones.matriz');
        $municipiosCoords = config('ubicaciones.municipios');

        foreach ($trayectosData as $datos) {
            $trayecto = Trayecto::create([
                'chofer_id' => $choferes[$datos['chofer']]->id,
                'carro_id' => $carros[$datos['carro']]->id,
                'pedido_id' => $pedidos[$datos['municipio']]->id,
                'estatus' => $datos['estatus'],
                'descripcion_ruta' => 'Entrega de calzado a Sucursal ' . $datos['municipio'],
            ]);

            // Para los trayectos "En ruta" se generan 2 posiciones de ejemplo
            // a lo largo de la línea matriz -> sucursal, para que el mapa ya
            // muestre al chofer moviéndose sin esperar a que abra /compartir.
            if ($datos['estatus'] === 'En ruta' && isset($municipiosCoords[$datos['municipio']])) {
                [$latDestino, $lngDestino] = $municipiosCoords[$datos['municipio']];

                // (no se usan floats como llave de array: PHP los trunca a
                // entero y se pisarían entre sí)
                foreach ([[0.4, 6], [0.7, 1]] as [$avance, $minutosAtras]) {
                    TrayectoUbicacion::create([
                        'trayecto_id' => $trayecto->id,
                        'latitud' => $matriz['lat'] + $avance * ($latDestino - $matriz['lat']),
                        'longitud' => $matriz['lng'] + $avance * ($lngDestino - $matriz['lng']),
                        'registrado_en' => now()->subMinutes($minutosAtras)->format('Y-m-d H:i:s'),
                    ]);
                }
            }

            // El link para compartir ubicación va firmado (expira y no se
            // puede fabricar a mano) — se genera desde "Link para el chofer"
            // en /trayecto/lista, no hay una URL fija que imprimir aquí.
            $this->command?->info("Trayecto #{$trayecto->id} ({$datos['municipio']}, {$datos['estatus']}) creado.");
        }
    }
}
