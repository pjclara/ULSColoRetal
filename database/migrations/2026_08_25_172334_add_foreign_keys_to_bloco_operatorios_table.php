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
        Schema::table('bloco_operatorios', function (Blueprint $table) {
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['deleted_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['internamento_id'])->references(['id'])->on('internamentos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['re_intervencao_nao_programada_id'])->references(['id'])->on('re_intervencao_nao_programadas')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['tipo_de_abordagem_id'])->references(['id'])->on('tipo_de_abordagems')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['tipo_de_cirurgia_id'])->references(['id'])->on('tipo_de_cirurgias')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['updated_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bloco_operatorios', function (Blueprint $table) {
            $table->dropForeign('bloco_operatorios_created_by_id_foreign');
            $table->dropForeign('bloco_operatorios_deleted_by_id_foreign');
            $table->dropForeign('bloco_operatorios_internamento_id_foreign');
            $table->dropForeign('bloco_operatorios_re_intervencao_nao_programada_id_foreign');
            $table->dropForeign('bloco_operatorios_tipo_de_abordagem_id_foreign');
            $table->dropForeign('bloco_operatorios_tipo_de_cirurgia_id_foreign');
            $table->dropForeign('bloco_operatorios_updated_by_id_foreign');
        });
    }
};
