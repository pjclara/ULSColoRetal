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
        Schema::table('referenciacao_centro_de_referencias', function (Blueprint $table) {
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['deleted_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['destino_id'])->references(['id'])->on('destinos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['origem_id'])->references(['id'])->on('origem_da_referenciacaos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['responsavel_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['updated_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['utente_id'])->references(['id'])->on('utentes')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('referenciacao_centro_de_referencias', function (Blueprint $table) {
            $table->dropForeign('referenciacao_centro_de_referencias_created_by_id_foreign');
            $table->dropForeign('referenciacao_centro_de_referencias_deleted_by_id_foreign');
            $table->dropForeign('referenciacao_centro_de_referencias_destino_id_foreign');
            $table->dropForeign('referenciacao_centro_de_referencias_origem_id_foreign');
            $table->dropForeign('referenciacao_centro_de_referencias_responsavel_id_foreign');
            $table->dropForeign('referenciacao_centro_de_referencias_updated_by_id_foreign');
            $table->dropForeign('referenciacao_centro_de_referencias_utente_id_foreign');
        });
    }
};
