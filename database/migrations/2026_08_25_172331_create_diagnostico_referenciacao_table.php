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
        Schema::create('diagnostico_referenciacao', function (Blueprint $table) {
            $table->unsignedBigInteger('diagnostico_id')->index('diagnostico_referenciacao_diagnostico_id_foreign');
            $table->unsignedBigInteger('referenciacao_id')->index('diagnostico_referenciacao_referenciacao_id_foreign');
            $table->date('data_diagnostico')->nullable();
            $table->unsignedBigInteger('modo_id')->index('diagnostico_referenciacao_modo_id_foreign');
            $table->bigIncrements('id');
            $table->unsignedBigInteger('histologia_id')->nullable()->index('diagnostico_referenciacao_histologia_id_foreign');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostico_referenciacao');
    }
};
