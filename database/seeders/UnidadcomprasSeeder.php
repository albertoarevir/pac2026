<?php

namespace Database\Seeders;

use App\Models\UnidadCompra;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnidadcomprasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir los nombres de los departamentos
        $unidadcompras = [
            'Departamento L.1.',
            'Departamento L.2.',
            'Departamento L.3.',
            'Departamento L.4.',
            'Departamento L.5.',
            'Departamento L.6.',
            'Departamento L.8.',
            'Dirección de Logística'
        ];

        // Insertar los departamentos en la base de datos
        foreach ($unidadcompras as $unidadcompra) {
            UnidadCompra::create([
                'detalle' => $unidadcompra, // Asegúrate de que el campo se llame 'nombre'
            ]);
        }
    }
}
