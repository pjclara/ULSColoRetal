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
        Schema::create('analises', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('grupo_analitico_id')->nullable()->comment('gupo analítico');
            $table->string('nome', 191)->comment('nome da analise');
            $table->string('unidades', 191)->nullable()->comment('unidades usadas');
            $table->decimal('limite_superior', 10)->nullable()->comment('limite superior');
            $table->decimal('limite_inferior', 10)->nullable()->comment('limite inferior');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analises');
    }
};
