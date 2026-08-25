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
        Schema::table('internamentos', function (Blueprint $table) {
            $table->foreign(['caso_social_id'])->references(['id'])->on('caso_socials')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['clavien_dindo_id'])->references(['id'])->on('clavien_dindos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['deleted_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['destino_id'])->references(['id'])->on('destinos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['estado_da_alta_id'])->references(['id'])->on('estado_da_altas')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['localizacao_id'])->references(['id'])->on('localizacaos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['origem_do_internamento_id'])->references(['id'])->on('origem_do_internamentos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['responsavel_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['updated_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['utente_id'])->references(['id'])->on('utentes')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internamentos', function (Blueprint $table) {
            $table->dropForeign('internamentos_caso_social_id_foreign');
            $table->dropForeign('internamentos_clavien_dindo_id_foreign');
            $table->dropForeign('internamentos_created_by_id_foreign');
            $table->dropForeign('internamentos_deleted_by_id_foreign');
            $table->dropForeign('internamentos_destino_id_foreign');
            $table->dropForeign('internamentos_estado_da_alta_id_foreign');
            $table->dropForeign('internamentos_localizacao_id_foreign');
            $table->dropForeign('internamentos_origem_do_internamento_id_foreign');
            $table->dropForeign('internamentos_responsavel_id_foreign');
            $table->dropForeign('internamentos_updated_by_id_foreign');
            $table->dropForeign('internamentos_utente_id_foreign');
        });
    }
};
