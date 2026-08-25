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
        Schema::create('intervencaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->unsignedBigInteger('grupo_intervencao_id')->index('intervencaos_grupo_intervencao_id_foreign');
            $table->string('abrev')->nullable();
            $table->boolean('centro_de_referencia')->default(false);
            $table->integer('cirurgia_de_ressecao')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intervencaos');
    }
};
