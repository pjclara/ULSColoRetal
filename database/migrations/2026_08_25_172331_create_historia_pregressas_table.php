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
        Schema::create('historia_pregressas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referenciacao_id')->index();
            $table->enum('inicio_queixas', ['1', '2', '3', '4', '5', '6', '99'])->comment('inicio das queixas');
            $table->date('data_diagnostico')->comment('data do diagnóstico');
            $table->integer('idade_diagnostico')->comment('idade à data do diagnóstico');
            $table->enum('metodo_diagnostico', ['1', '2', '3', '4', '5', '99'])->comment('qual o metodo de diagnostico');
            $table->boolean('bloquear_tabela')->default(false)->comment('Se dados estão bloqueados');
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('historia_pregressas_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('historia_pregressas_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('historia_pregressas_deleted_by_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historia_pregressas');
    }
};
