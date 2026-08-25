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
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['deleted_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['estado_de_agendamento_id'])->references(['id'])->on('estado_de_agendamentos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['lista_de_espera_id'])->references(['id'])->on('lista_de_esperas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['local_de_agendamento_id'])->references(['id'])->on('local_de_agendamentos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['periodo_de_agendamento_id'])->references(['id'])->on('periodo_de_agendamentos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['responsavel_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['sala_de_agendamento_id'])->references(['id'])->on('sala_de_agendamentos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['tipo_de_agendamento_id'])->references(['id'])->on('tipo_de_agendamentos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['updated_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropForeign('agendamentos_created_by_id_foreign');
            $table->dropForeign('agendamentos_deleted_by_id_foreign');
            $table->dropForeign('agendamentos_estado_de_agendamento_id_foreign');
            $table->dropForeign('agendamentos_lista_de_espera_id_foreign');
            $table->dropForeign('agendamentos_local_de_agendamento_id_foreign');
            $table->dropForeign('agendamentos_periodo_de_agendamento_id_foreign');
            $table->dropForeign('agendamentos_responsavel_id_foreign');
            $table->dropForeign('agendamentos_sala_de_agendamento_id_foreign');
            $table->dropForeign('agendamentos_tipo_de_agendamento_id_foreign');
            $table->dropForeign('agendamentos_updated_by_id_foreign');
        });
    }
};
