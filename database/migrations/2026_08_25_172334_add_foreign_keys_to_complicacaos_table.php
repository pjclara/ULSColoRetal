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
        Schema::table('complicacaos', function (Blueprint $table) {
            $table->foreign(['grupo_complicacao_id'])->references(['id'])->on('grupo_complicacaos')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complicacaos', function (Blueprint $table) {
            $table->dropForeign('complicacaos_grupo_complicacao_id_foreign');
        });
    }
};
