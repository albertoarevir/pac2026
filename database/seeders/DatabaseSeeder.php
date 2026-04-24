<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Clasificador;
use App\Models\EstadoCompra;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    { 
        
        $this->call([
            ClasificadorsSeeder::class,
            DepartamentosSeeder::class,
            EspeciesSeeder::class,
            EstadosSeeder::class,
            ItemsSeeder::class,
            ModalidadesSeeder::class,
            UnidadcomprasSeeder::class,
            EstadolicitacionSeeder::class,
            EstadocomprasSeeder::class,
            RoleSeeder::class,
            EstadosModificacionSeeder::class,
            FuenteFinanciamientoSeeder::class,
        ]);
        
        
        
        User::create([
            'Rut' => '12924660K',
            'name' => 'Alberto Rivera',
            'email' => 'admin@admin.com',
            'departamento_id' => '7',
            'password' => Hash::make('12345678')
        ])->assignRole('ADMINISTRADOR');

     }

}