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
        Schema::create('radioterapias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referenciacao_id')->index('radioterapias_referenciacao_id_foreign');
            $table->date('dataInicio')->comment('data de inicio');
            $table->date('dataFim')->nullable()->comment('data de fim');
            $table->enum('tipo', ['1', '2', '99'])->comment('tipo da Radioterapia');
            $table->enum('objectivo', ['1', '2', '3', '5', '6', '98', '99'])->comment('objectivo da Radioterapia');
            $table->integer('total_dose')->comment('total da dose');
            $table->integer('total_fracoes')->comment('total de fracções');
            $table->enum('toxicidade', ['1', '2', '3', '99'])->comment('se tem toxicidade da Radioterapia');
            $table->enum('toxicidade_grau', ['0', '1', '2'])->nullable()->comment('grau da toxicidade');
            $table->enum('toxicidade_tipo', ['1', '2', '3', '98', '99'])->nullable()->comment('descrição da toxicidade');
            $table->boolean('completou_rt')->comment('se completou a radioterapia');
            $table->enum('suspensao_rt_causa', ['1', '2', '98', '99'])->nullable()->comment('causa da suspensão da radioterapia');
            $table->boolean('bloquear_tabela')->default(false)->comment('Se dados estão bloqueados');
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('radioterapias_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('radioterapias_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('radioterapias_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radioterapias');
    }
};
