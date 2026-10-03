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
        Schema::table('resolucao_complicacaos', function (Blueprint $table) {
            // Grau Clavien-Dindo tipicamente associado a este tratamento/resolução — usado para
            // sugerir a classificação no formulário de internamento (não é vinculativo).
            $table->unsignedBigInteger('clavien_dindo_id')->nullable()->after('nome');
            $table->foreign('clavien_dindo_id')->references('id')->on('clavien_dindos')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resolucao_complicacaos', function (Blueprint $table) {
            $table->dropForeign(['clavien_dindo_id']);
            $table->dropColumn('clavien_dindo_id');
        });
    }
};
