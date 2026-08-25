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
        Schema::table('diagnostico_referenciacao', function (Blueprint $table) {
            $table->foreign(['diagnostico_id'])->references(['id'])->on('diagnosticos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['histologia_id'])->references(['id'])->on('histologias')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['modo_id'])->references(['id'])->on('diagnostico_referenciacao_modos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['referenciacao_id'])->references(['id'])->on('referenciacao_centro_de_referencias')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diagnostico_referenciacao', function (Blueprint $table) {
            $table->dropForeign('diagnostico_referenciacao_diagnostico_id_foreign');
            $table->dropForeign('diagnostico_referenciacao_histologia_id_foreign');
            $table->dropForeign('diagnostico_referenciacao_modo_id_foreign');
            $table->dropForeign('diagnostico_referenciacao_referenciacao_id_foreign');
        });
    }
};
