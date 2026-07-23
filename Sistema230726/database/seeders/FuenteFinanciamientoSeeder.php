<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FuenteFinanciamientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('fuente_financiamiento')->insert([
            ['detalle' => 'Presupuesto Inercial'],
            ['detalle' => 'Convenio Gore'],
            ['detalle' => 'Fora'],           
            ['detalle' => 'Presupuesto Extraordinario'],
            ['detalle' => 'F.N.D.R.'],
            
        ]);
    }
}
