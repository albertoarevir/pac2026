<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento; // Asegúrate de importar el modelo Departamento

class DepartamentosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir los nombres de los departamentos
        $departamentos = [           
            'Departamento L.1.',
            'Departamento L.2.',
            'Departamento L.3.',
            'Departamento L.4.',
            'Departamento L.5.',
            'Departamento L.6.',           
            'Direccion de Logistica',
        ];

        // Insertar los departamentos en la base de datos
        foreach ($departamentos as $departamento) {
            Departamento::create([
                'detalle' => $departamento, // Asegúrate de que el campo se llame 'nombre'
            ]);
        }
    }
}
