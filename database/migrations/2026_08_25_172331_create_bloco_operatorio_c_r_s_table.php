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
        Schema::create('bloco_operatorio_c_r_s', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bloco_operatorio_id')->index('bloco_operatorio_c_r_s_bloco_operatorio_id_foreign');
            $table->integer('experiencia_cirurgiao_id')->nullable();
            $table->integer('asa')->nullable();
            $table->decimal('mortalidade', 5)->nullable();
            $table->integer('score_fisiologico')->nullable();
            $table->integer('score_gravidade_cirurgico')->nullable();
            $table->integer('preparacao_intestinal_id')->nullable();
            $table->unsignedBigInteger('intencao_id')->index('bloco_operatorio_c_r_s_intencao_id_foreign');
            $table->longText('paleativa_causa')->nullable();
            $table->integer('duracao');
            $table->unsignedBigInteger('estoma_de_protecao_id')->index('bloco_operatorio_c_r_s_estoma_de_protecao_id_foreign');
            $table->unsignedBigInteger('local_extracao_peca_id')->index('bloco_operatorio_c_r_s_local_extracao_peca_id_foreign');
            $table->unsignedBigInteger('tipo_de_dreno_id')->index('bloco_operatorio_c_r_s_tipo_de_dreno_id_foreign');
            $table->enum('resseccao_multi_orgao', ['1', '2', '99']);
            $table->text('resseccao_multi_orgao_quais')->nullable();
            $table->unsignedBigInteger('aderencia_id')->index('bloco_operatorio_c_r_s_aderencia_id_foreign');
            $table->unsignedBigInteger('tipo_de_resseccao_id')->index('bloco_operatorio_c_r_s_tipo_de_resseccao_id_foreign');
            $table->unsignedBigInteger('neoplasia_residual_id')->index('bloco_operatorio_c_r_s_neoplasia_residual_id_foreign');
            $table->unsignedBigInteger('perdas_hematica_id')->index('bloco_operatorio_c_r_s_perdas_hematica_id_foreign');
            $table->enum('transfusao_intra_operatoria', ['1', '2', '99']);
            $table->integer('unidades_globulos')->nullable();
            $table->enum('protector_de_parede', ['1', '2', '99']);
            $table->enum('complicacoes', ['1', '2', '99']);
            $table->text('complicacoes_quais')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('bloco_operatorio_c_r_s_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('bloco_operatorio_c_r_s_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('bloco_operatorio_c_r_s_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloco_operatorio_c_r_s');
    }
};
