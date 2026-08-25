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
        Schema::create('reuniao_decisao_terapeuticas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referenciacao_id')->index('reuniao_decisao_terapeuticas_referenciacao_id_foreign');
            $table->date('data_de_decisao_terapeutica');
            $table->unsignedBigInteger('timming_id')->nullable()->index('reuniao_decisao_terapeuticas_timming_id_foreign');
            $table->unsignedBigInteger('estado_consulta_id')->index('reuniao_decisao_terapeuticas_estado_consulta_id_foreign');
            $table->longText('recados')->nullable();
            $table->longText('resumo')->nullable();
            $table->longText('comentarios')->nullable();
            $table->boolean('bloquear_tabela')->nullable()->default(false);
            $table->date('envidada_em')->nullable();
            $table->date('vista_em')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('reuniao_decisao_terapeuticas_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('reuniao_decisao_terapeuticas_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('reuniao_decisao_terapeuticas_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reuniao_decisao_terapeuticas');
    }
};
