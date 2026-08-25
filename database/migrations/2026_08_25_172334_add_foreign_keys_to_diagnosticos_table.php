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
        Schema::table('diagnosticos', function (Blueprint $table) {
            $table->foreign(['grupo_diagnostico_id'])->references(['id'])->on('grupo_diagnosticos')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['tipo_de_diagnostico_id'])->references(['id'])->on('tipo_de_diagnosticos')->onUpdate('restrict')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diagnosticos', function (Blueprint $table) {
            $table->dropForeign('diagnosticos_grupo_diagnostico_id_foreign');
            $table->dropForeign('diagnosticos_tipo_de_diagnostico_id_foreign');
        });
    }
};
