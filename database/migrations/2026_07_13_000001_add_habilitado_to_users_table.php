<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('habilitado')->default(true)->after('Dotacion');
        });

        // Por seguridad, al introducir este campo se deshabilita a todos los usuarios
        // excepto a quienes tengan el rol ADMINISTRADOR, para no bloquear el acceso al sistema.
        $adminUserIds = DB::table('model_has_roles')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('roles.name', 'ADMINISTRADOR')
            ->where('model_has_roles.model_type', \App\Models\User::class)
            ->pluck('model_has_roles.model_id');

        DB::table('users')
            ->whereNotIn('id', $adminUserIds)
            ->update(['habilitado' => false]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('habilitado');
        });
    }
};
