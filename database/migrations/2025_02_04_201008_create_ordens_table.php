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
        Schema::create('ordens', function (Blueprint $table) {
            $table->id();
           // $table->string('modalidad');
            $table->string('numero');
            $table->text('observacion')->nullable(); // Permite valores null
            $table->string('monto');
            $table->date('fecha_seguimiento')->nullable();
            $table->unsignedBigInteger('id_proyecto');
            $table->unsignedBigInteger('id_licitacion')->nullable();
            $table->unsignedBigInteger('estado_id');
            $table->foreign('id_proyecto')->references('id')->on('pacs')->onDelete('cascade');
            $table->foreign('id_licitacion')->references('id')->on('modalidads')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('ordenes', function (Blueprint $table) {
            $table->dropColumn('fecha_seguimiento');
        });
    }
};
