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
        Schema::create('diagnosticos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->unsignedBigInteger('grupo_diagnostico_id')->index('diagnosticos_grupo_diagnostico_id_foreign');
            $table->unsignedBigInteger('tipo_de_diagnostico_id')->index('diagnosticos_tipo_de_diagnostico_id_foreign');
            $table->string('abrev')->nullable();
            $table->boolean('centro_de_referencia')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosticos');
    }
};
