<?php

namespace Database\Seeders;

use App\Models\Clasificador;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClasificadorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
     public function run(): void
     {
         // Definir los datos de los clasificadores
         $clasificadors = [
             ['codigo_id' => 22, 'detalle' => 'BIENES Y SERVICIOS DE CONSUMO'],
             ['codigo_id' => 23, 'detalle' => 'PRESTACIONES DE SEGURIDAD SOCIAL'],
             ['codigo_id' => 24, 'detalle' => 'TRANSFERENCIAS CORRIENTES'],
             ['codigo_id' => 25, 'detalle' => 'INTEGROS AL FISCO'],
             ['codigo_id' => 26, 'detalle' => 'OTROS GASTOS CORRIENTES'],
             ['codigo_id' => 29, 'detalle' => 'ADQUISICION DE ACTIVOS NO FINANCIEROS'],
             ['codigo_id' => 31, 'detalle' => 'INICIATIVAS DE INVERSION'],
             ['codigo_id' => 32, 'detalle' => 'PRESTAMOS'],
             ['codigo_id' => 34, 'detalle' => 'SERVICIO DE LA DEUDA'],
         ];
 
         // Insertar los clasificadores en la base de datos
         foreach ($clasificadors as $clasificador) {
             Clasificador::create([
                 'codigo_id' => $clasificador['codigo_id'],
                 'detalle' => $clasificador['detalle'],
             ]);
         }
     }
 }

