<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pacs', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->string('departamento_id'); 
            $table->string('especie_id'); 
            $table->integer('cantidad'); 
            $table->decimal('presupuesto', 15, 0);
            $table->string('clasificador');
            $table->string('codigo');
          //  $table->string('unidadcompra');
            $table->unsignedBigInteger('estado_id'); // Asegúrate de que sea del mismo tipo que la columna `id` en `estados`
            $table->text('observaciones')->nullable();  
            $table->string('estado_modificacion', 30)->nullable();         
            $table->unsignedBigInteger('fuente_financiamiento'); // Nueva columna para la fuente de financiamiento
            $table->timestamps();
        
            // Clave foránea
            $table->foreign('estado_id')->references('id')->on('estados')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacs');
    }
};
