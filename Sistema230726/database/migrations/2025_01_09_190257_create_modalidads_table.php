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
        Schema::create('modalidads', function (Blueprint $table) {
            $table->id();
            $table->string('modalidad');
            $table->string('numero');
            $table->text('observacion')->nullable(); // Permite valores null
            $table->unsignedBigInteger('id_proyecto');
            $table->unsignedBigInteger('estado_id');
            //$table->foreignId('estado_id')->constrained()->onDelete('cascade'); // Clave foránea
           //$table->foreign('estado_licitacion_id')->references('id')->on('estado_licitacions')->onDelete('cascade');

            //$table->foreignId('estado_id')->references('id')->on('estado_licitacions')->onDelete('cascade');
            $table->foreign('id_proyecto')->references('id')->on('pacs')->onDelete('cascade');
            $table->timestamps();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modalidads');
    }
};
