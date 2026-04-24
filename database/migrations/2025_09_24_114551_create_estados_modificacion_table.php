<?php

// database/migrations/YYYY_MM_DD_create_estados_modificacion_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estados_modificacion', function (Blueprint $table) {
            $table->id();
            $table->string('detalle');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estados_modificacion');
    }
};