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
        Schema::create('historia_pregressa_queixa', function (Blueprint $table) {
            $table->unsignedBigInteger('historia_pregressa_id');
            $table->unsignedBigInteger('queixa_id')->index('historia_pregressa_queixa_queixa_id_foreign');

            $table->unique(['historia_pregressa_id', 'queixa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historia_pregressa_queixa');
    }
};
