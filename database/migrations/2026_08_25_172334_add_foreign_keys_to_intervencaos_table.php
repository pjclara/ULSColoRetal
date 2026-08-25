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
        Schema::table('intervencaos', function (Blueprint $table) {
            $table->foreign(['grupo_intervencao_id'])->references(['id'])->on('grupo_intervencaos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intervencaos', function (Blueprint $table) {
            $table->dropForeign('intervencaos_grupo_intervencao_id_foreign');
        });
    }
};
