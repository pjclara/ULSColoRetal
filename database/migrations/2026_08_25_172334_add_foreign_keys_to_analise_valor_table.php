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
        Schema::table('analise_valor', function (Blueprint $table) {
            $table->foreign(['analise_id'])->references(['id'])->on('analises')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['analise_referenciacao_id'])->references(['id'])->on('analise_referenciacaos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analise_valor', function (Blueprint $table) {
            $table->dropForeign('analise_valor_analise_id_foreign');
            $table->dropForeign('analise_valor_analise_referenciacao_id_foreign');
        });
    }
};
