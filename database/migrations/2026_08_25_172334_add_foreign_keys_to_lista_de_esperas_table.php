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
        Schema::table('lista_de_esperas', function (Blueprint $table) {
            $table->foreign(['created_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['deleted_by_id'])->references(['id'])->on('users')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('lista_de_esperas', function (Blueprint $table) {
            $table->dropForeign('lista_de_esperas_created_by_id_foreign');
            $table->dropForeign('lista_de_esperas_deleted_by_id_foreign');
            $table->dropForeign('lista_de_esperas_responsavel_id_foreign');
            $table->dropForeign('lista_de_esperas_updated_by_id_foreign');
            $table->dropForeign('lista_de_esperas_utente_id_foreign');
        });
    }
};
