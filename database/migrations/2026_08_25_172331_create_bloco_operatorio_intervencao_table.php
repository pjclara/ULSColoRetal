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
        Schema::create('bloco_operatorio_intervencao', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bloco_operatorio_id');
            $table->unsignedBigInteger('intervencao_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloco_operatorio_intervencao');
    }
};
