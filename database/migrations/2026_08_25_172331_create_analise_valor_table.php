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
        Schema::create('analise_valor', function (Blueprint $table) {
            $table->unsignedBigInteger('analise_id');
            $table->unsignedBigInteger('analise_referenciacao_id')->index('analise_valor_analise_referenciacao_id_foreign');
            $table->decimal('valor');

            $table->unique(['analise_id', 'analise_referenciacao_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analise_valor');
    }
};
