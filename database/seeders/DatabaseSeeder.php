<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Asegura que exista la sucursal matriz para que la lógica de roles
        // y permisos del proyecto funcione sin depender de un nombre manual.
        $matriz = \App\Models\Sucursal::firstOrCreate(
            ['nombre' => config('ubicaciones.matriz.nombre')],
            [
                'empleado_id' => null,
                'calle' => 'Av. Principal',
                'numero' => 1,
                'municipio' => 'Guadalajara',
                'codigo_postal' => '44100',
                'contacto' => '3330000000',
                'imagen' => 'sin-imagen.jpg',
                'estatus' => 'Activo',
                'es_matriz' => true,
            ]
        );

        $matriz->update(['es_matriz' => true]);
    }
}
