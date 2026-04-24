<?php

namespace Database\Seeders;

use App\Models\Tipocompra;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModalidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tipos = [
            '(L1) Licitación Pública Menor a 100 UTM',
            '(LE) Licitación Pública Entre 100 y 1000 UTM',
            '(LP) Licitación Pública igual o superior a 1.000 UTM e inferior a 2.000 UTM',
            '(LQ) Licitación Pública igual o superior a 2.000 UTM e inferior a 5.000 UTM',
            '(LR) Licitación Pública igual o superior a 5.000 UTM',
            '(LS) Licitación Pública Servicios personales especializados',
            '(O1) Licitación Pública de Obras',
            '(E2) Licitación Privada Inferior a 100 UTM',
            '(CO) Licitación Privada igual o superior a 100 UTM e inferior a 1000 UTM',
            '(B2) Licitación Privada igual o superior a 1000 UTM e inferior a 2000 UTM',
            '(H2) Licitación Privada igual o superior a 2000 UTM e inferior a 5000 UTM',
            '(I2) Licitación Privada Mayor a 5000 UTM',
            '(O2) Licitación Privada de Obras',
            'Convenio Marco',
            'Trato Directo',
            'Compra Agil',
        ];

        foreach ($tipos as $tipo) {
            Tipocompra::create([
                'detalle' => $tipo, // Asegúrate de que el campo se llame 'nombre'
            ]);
        }
    }
}
