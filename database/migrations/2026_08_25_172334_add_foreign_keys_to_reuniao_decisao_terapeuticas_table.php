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
        Schema::table('reuniao_decisao_terapeuticas', function (Blueprint $table) {
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['deleted_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['estado_consulta_id'])->references(['id'])->on('estado_consultas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['referenciacao_id'])->references(['id'])->on('referenciacao_centro_de_referencias')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['timming_id'])->references(['id'])->on('timmings')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['updated_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reuniao_decisao_terapeuticas', function (Blueprint $table) {
            $table->dropForeign('reuniao_decisao_terapeuticas_created_by_id_foreign');
            $table->dropForeign('reuniao_decisao_terapeuticas_deleted_by_id_foreign');
            $table->dropForeign('reuniao_decisao_terapeuticas_estado_consulta_id_foreign');
            $table->dropForeign('reuniao_decisao_terapeuticas_referenciacao_id_foreign');
            $table->dropForeign('reuniao_decisao_terapeuticas_timming_id_foreign');
            $table->dropForeign('reuniao_decisao_terapeuticas_updated_by_id_foreign');
        });
    }
};
