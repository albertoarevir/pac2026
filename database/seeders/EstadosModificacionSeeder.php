<?php

// database/seeders/EstadosModificacionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosModificacionSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('estados_modificacion')->insert([
            ['detalle' => 'Ingreso Inicial'],
            ['detalle' => 'Primera Modificación'],
            ['detalle' => 'Segunda Modificación'],
            ['detalle' => 'Tercera Modificación'],
            ['detalle' => 'Cuarta Modificación'],
        ]);
    }
}