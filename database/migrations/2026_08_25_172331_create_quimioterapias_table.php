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
        Schema::create('quimioterapias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referenciacao_id')->index('quimioterapias_referenciacao_id_foreign');
            $table->date('dataInicio')->nullable()->comment('data de inicio');
            $table->date('dataFim')->nullable()->comment('data de fim');
            $table->enum('tipo', ['1', '2', '3', '99'])->comment('tipo da quimioterapia');
            $table->enum('objectivo', ['1', '2', '99'])->comment('objectivo da quimioterapia');
            $table->enum('esquema', ['1', '2', '3', '4', '99'])->comment('regime da quimioterapia');
            $table->enum('toxicidade', ['1', '2', '3', '99'])->nullable()->comment('se tem toxicidade da quimioterapia');
            $table->enum('toxicidade_tipo', ['1', '2', '3', '98', '99'])->nullable()->comment('grau da toxicidade');
            $table->enum('toxicidade_alteracao_qt', ['1', '2', '3', '98', '99'])->nullable()->comment('descrição da toxicidade');
            $table->boolean('completou_qt')->comment('se completou a quimioterapia');
            $table->enum('suspensao_qt_causa', ['1', '2', '98', '99'])->nullable()->comment('causa da suspensão da quimioterapia');
            $table->boolean('bloquear_tabela')->default(false)->comment('Se dados estão bloqueados');
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('quimioterapias_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('quimioterapias_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('quimioterapias_deleted_by_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quimioterapias');
    }
};
