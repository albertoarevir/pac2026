<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bitacoras', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained();
    $table->string('modulo');
    $table->string('proyecto_id')->nullable();
    $table->string('accion');
    $table->text('descripcion')->nullable();
    // Nuevos campos para el detalle
    $table->text('campo_anterior')->nullable();   
    $table->text('campo_modificado')->nullable(); 
    $table->string('ip');
    $table->string('user_agent');
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacoras');
    }
};
