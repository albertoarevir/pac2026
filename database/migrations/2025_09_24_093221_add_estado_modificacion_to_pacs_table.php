<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 /**
    public function up(): void
    {
        Schema::table('pacs', function (Blueprint $table) {
            $table->string('estado_modificacion', 30)->nullable();
        });
    }
 */

public function up(): void
{
    // Verifica si la columna NO existe antes de intentar crearla
    if (!Schema::hasColumn('pacs', 'estado_modificacion')) {
        Schema::table('pacs', function (Blueprint $table) {
            $table->string('estado_modificacion', 30)->nullable();
        });
    }
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pacs', function (Blueprint $table) {
            $table->dropColumn('estado_modificacion');
        });
    }
};