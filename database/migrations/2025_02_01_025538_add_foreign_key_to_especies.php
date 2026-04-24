<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('especies', function (Blueprint $table) {
            $table->foreign('departamento_id')->references('id')->on('departamentos');
        });
    }

    public function down()
    {
        Schema::table('especies', function (Blueprint $table) {
            $table->dropForeign('fk_departamento_id');
        });
    }
};
