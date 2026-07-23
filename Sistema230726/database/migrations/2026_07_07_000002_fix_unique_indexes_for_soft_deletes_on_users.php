<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Eliminar los índices únicos globales (no respetan soft deletes)
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_rut_unique');
            $table->dropUnique('users_email_unique');
        });

        // Crear índices únicos parciales: solo aplican a registros NO eliminados
        DB::statement('CREATE UNIQUE INDEX users_rut_unique ON users ("Rut") WHERE deleted_at IS NULL');
        DB::statement('CREATE UNIQUE INDEX users_email_unique ON users (email) WHERE deleted_at IS NULL');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS users_rut_unique');
        DB::statement('DROP INDEX IF EXISTS users_email_unique');

        Schema::table('users', function (Blueprint $table) {
            $table->unique('Rut');
            $table->unique('email');
        });
    }
};
