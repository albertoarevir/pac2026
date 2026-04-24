<?php

namespace Database\Seeders;

use App\Models\Estado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Definir los nombres de los departamentos
        $estados = [
            'Aprobado Dilocar',
            'Rechazado Dilocar',
            'Pendiente exponer Dilocar',
            'Reevaluar',
        ];

        // Insertar los departamentos en la base de datos
        foreach ($estados as $estado) {
            Estado::create([
                'detalle' => $estado, // Asegúrate de que el campo se llame 'nombre'
            ]);
        }
    }
}
