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
        Schema::create('complicacao_internamento', function (Blueprint $table) {
            $table->unsignedBigInteger('complicacao_id');
            $table->unsignedBigInteger('internamento_id');
            $table->json('resolucao')->nullable()->comment('resolução da complicação');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complicacao_internamento');
    }
};
