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
        Schema::table('bloco_operatorio_c_r_s', function (Blueprint $table) {
            $table->foreign(['aderencia_id'])->references(['id'])->on('aderencias')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['bloco_operatorio_id'])->references(['id'])->on('bloco_operatorios')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['deleted_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['estoma_de_protecao_id'])->references(['id'])->on('estoma_de_protecaos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['intencao_id'])->references(['id'])->on('intencaos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['local_extracao_peca_id'])->references(['id'])->on('local_extracao_pecas')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['neoplasia_residual_id'])->references(['id'])->on('neoplasia_residuals')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['perdas_hematica_id'])->references(['id'])->on('perdas_hematicas')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['tipo_de_dreno_id'])->references(['id'])->on('tipo_de_drenos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['tipo_de_resseccao_id'])->references(['id'])->on('tipo_de_resseccaos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['updated_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bloco_operatorio_c_r_s', function (Blueprint $table) {
            $table->dropForeign('bloco_operatorio_c_r_s_aderencia_id_foreign');
            $table->dropForeign('bloco_operatorio_c_r_s_bloco_operatorio_id_foreign');
            $table->dropForeign('bloco_operatorio_c_r_s_created_by_id_foreign');
            $table->dropForeign('bloco_operatorio_c_r_s_deleted_by_id_foreign');
            $table->dropForeign('bloco_operatorio_c_r_s_estoma_de_protecao_id_foreign');
            $table->dropForeign('bloco_operatorio_c_r_s_intencao_id_foreign');
            $table->dropForeign('bloco_operatorio_c_r_s_local_extracao_peca_id_foreign');
            $table->dropForeign('bloco_operatorio_c_r_s_neoplasia_residual_id_foreign');
            $table->dropForeign('bloco_operatorio_c_r_s_perdas_hematica_id_foreign');
            $table->dropForeign('bloco_operatorio_c_r_s_tipo_de_dreno_id_foreign');
            $table->dropForeign('bloco_operatorio_c_r_s_tipo_de_resseccao_id_foreign');
            $table->dropForeign('bloco_operatorio_c_r_s_updated_by_id_foreign');
        });
    }
};
