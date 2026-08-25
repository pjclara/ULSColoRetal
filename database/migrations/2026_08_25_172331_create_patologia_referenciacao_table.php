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
        Schema::create('patologia_referenciacao', function (Blueprint $table) {
            $table->unsignedBigInteger('patologia_id');
            $table->unsignedBigInteger('referenciacao_id')->index('patologia_referenciacao_referenciacao_id_foreign');

            $table->index(['patologia_id', 'referenciacao_id']);
            $table->unique(['patologia_id', 'referenciacao_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patologia_referenciacao');
    }
};
