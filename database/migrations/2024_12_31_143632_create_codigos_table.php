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
        Schema::create('codigos', function (Blueprint $table) {
            $table->id();           
            $table->string('codigopre', 10);
            $table->string('detalle', length:100);
           // $table->foreignId('codigo_id')->constrained('clasificadors');
          //  $table->foreignId('codigo_id')->constrained('clasificadors')->onDelete('cascade')->onUpdate('cascade');
            $table->string('codigo_id', 10);  
          $table->timestamps();        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigos');
    }
};