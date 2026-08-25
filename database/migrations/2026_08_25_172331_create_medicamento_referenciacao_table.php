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
        Schema::create('medicamento_referenciacao', function (Blueprint $table) {
            $table->unsignedBigInteger('referenciacao_id')->index('medicamento_referenciacao_referenciacao_id_foreign');
            $table->unsignedBigInteger('medicamento_id')->index('medicamento_referenciacao_medicamento_id_foreign');
            $table->string('comentario', 191)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicamento_referenciacao');
    }
};
