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
        Schema::create('lista_patologias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('patologia', 191);
            $table->string('sistema_id', 191);
            $table->integer('pontos')->default(0)->comment('para cálculo da Índice de Charlton comorbilidade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lista_patologias');
    }
};
