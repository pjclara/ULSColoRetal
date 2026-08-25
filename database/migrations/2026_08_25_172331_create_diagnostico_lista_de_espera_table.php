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
        Schema::create('diagnostico_lista_de_espera', function (Blueprint $table) {
            $table->unsignedBigInteger('diagnostico_id');
            $table->unsignedBigInteger('lista_de_espera_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostico_lista_de_espera');
    }
};
