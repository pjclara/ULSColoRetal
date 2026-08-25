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
        Schema::table('intervencao_descricaos', function (Blueprint $table) {
            $table->foreign(['anastemose_modo_id'])->references(['id'])->on('anastemose_modos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['anastemose_sentido_id'])->references(['id'])->on('anastemose_sentidos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['anastemose_via_id'])->references(['id'])->on('anastemose_vias')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['confirmacao_anastemose_id'])->references(['id'])->on('confirmacao_anastemoses')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['deleted_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['intervencao_id'])->references(['id'])->on('bloco_operatorio_intervencao')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['localizacao_anastemose_id'])->references(['id'])->on('localizacao_anastemoses')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['qualidade_peca_operatoria_id'])->references(['id'])->on('qualidade_peca_operatorias')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['tipo_de_reconstrucao_id'])->references(['id'])->on('tipo_de_reconstrucaos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['updated_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intervencao_descricaos', function (Blueprint $table) {
            $table->dropForeign('intervencao_descricaos_anastemose_modo_id_foreign');
            $table->dropForeign('intervencao_descricaos_anastemose_sentido_id_foreign');
            $table->dropForeign('intervencao_descricaos_anastemose_via_id_foreign');
            $table->dropForeign('intervencao_descricaos_confirmacao_anastemose_id_foreign');
            $table->dropForeign('intervencao_descricaos_created_by_id_foreign');
            $table->dropForeign('intervencao_descricaos_deleted_by_id_foreign');
            $table->dropForeign('intervencao_descricaos_intervencao_id_foreign');
            $table->dropForeign('intervencao_descricaos_localizacao_anastemose_id_foreign');
            $table->dropForeign('intervencao_descricaos_qualidade_peca_operatoria_id_foreign');
            $table->dropForeign('intervencao_descricaos_tipo_de_reconstrucao_id_foreign');
            $table->dropForeign('intervencao_descricaos_updated_by_id_foreign');
        });
    }
};
