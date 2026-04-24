<?php

namespace Database\Seeders;

use App\Models\EstadoCompra;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadocomprasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estadocompras = [
            'Orden de Compra Enviada',
            'Orden de Compra Autorizada',
            'Espera de Recepción Especie o Servicio',
            'Control de Calidad',
            'Recepción Conforme',
            'Enviada a Pago',
            'Finalizado',
            'Proyecto No Ejecutado',
            'Orden de Compra Cancelada',
        ];

        // Insertar los departamentos en la base de datos
        foreach ($estadocompras as $estado) {
            EstadoCompra::create([
                'detalle' => $estado,
            ]);
        }
    }
}