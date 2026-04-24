<?php

namespace Database\Seeders;

use App\Models\Codigo;
use App\Models\Tipocompra;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['codigopre' => '21', 'detalle' => 'GASTOS EN PERSONAL', 'codigo_id' => 21, 'created_at' => now(), 'updated_at' => now()],
            ['codigopre' => '21.01', 'detalle' => 'Personal de Planta', 'codigo_id' => 21, 'created_at' => now(), 'updated_at' => now()],
            ['codigopre' => '21.01.001', 'detalle' => 'Sueldos y Sobresueldos', 'codigo_id' => 21, 'created_at' => now(), 'updated_at' => now()],
            ['codigopre' => '21.01.001.001', 'detalle' => 'Sueldos Bases', 'codigo_id' => 21, 'created_at' => now(), 'updated_at' => now()],
            ['codigopre' => '21.01.001.003', 'detalle' => 'Asignación Profesional', 'codigo_id' => 21, 'created_at' => now(), 'updated_at' => now()],
            ['codigopre' => '21.01.001.004', 'detalle' => 'Asignación de Zona', 'codigo_id' => 21, 'created_at' => now(), 'updated_at' => now()],
            ['codigopre' => '21.01.001.005', 'detalle' => 'Asignación de Rancho', 'codigo_id' => 21, 'created_at' => now(), 'updated_at' => now()],
            ['codigopre' => '21.01.001.011', 'detalle' => 'Asignación de Movilización', 'codigo_id' => 21, 'created_at' => now(), 'updated_at' => now()],
            ['codigopre' => '21.01.001.014', 'detalle' => 'Asignaciones Compensatorias', 'codigo_id' => 21, 'created_at' => now(), 'updated_at' => now()],

        ];

        // Insertar los codigos en la base de datos
        foreach ($items as $item) {
            Codigo::create([
                'codigopre' => $item['codigopre'],
                'detalle' => $item['detalle'],
                'codigo_id' => $item['codigo_id'],
            ]);
        }
    }
}
