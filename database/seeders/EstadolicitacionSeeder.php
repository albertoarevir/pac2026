<?php

namespace Database\Seeders;

use App\Models\EstadoLicitacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadolicitacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estadosLicitacion = [
            'Publicada',
            'Cerrada',
            'Desierta',
            'Adjudicada',
            'Revocada',
            'Suspendida',
            'Preparación de Antecedentes',
            'Proyecto No Ejecutado',
        ];
         // Insertar los departamentos en la base de datos
         foreach ($estadosLicitacion as $estado) {
            EstadoLicitacion::create([
                'detalle' => $estado, // Asegúrate de que el campo se llame 'nombre'
            ]);
        }
    }
}
