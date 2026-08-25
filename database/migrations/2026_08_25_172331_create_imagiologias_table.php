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
        Schema::create('imagiologias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referenciacao_id')->index('imagiologias_referenciacao_id_foreign');
            $table->date('data_imagiologia')->comment('data da immagiologia');
            $table->enum('local_imagiologia', ['1', '2', '99'])->comment('local de realização');
            $table->enum('objectivo_imagiologia', ['1', '5', '2', '3', '4', '99'])->comment('objectivo de realização');
            $table->enum('tipo_imagiologia', ['1', '2', '99'])->comment('tipo de realização');
            $table->enum('exame_realizado', ['1', '2', '3', '4', '99'])->comment('exame realizado');
            $table->set('zona_avaliada', ['1', '2', '3', '4', '5', '99'])->comment('zona avaliada');
            $table->enum('lesao_visivel', ['1', '2', '3', '99'])->nullable()->comment('se lesão visível');
            $table->enum('metastizacao', ['1', '2', '3', '99'])->nullable()->comment('se tem metastização');
            $table->set('local_metastizacao', ['1', '2', '3', '4', '5', '6', '7', '99'])->nullable()->comment('local metastização');
            $table->boolean('bloquear_tabela')->default(false)->comment('Se dados estão bloqueados');
            $table->enum('complicacoes', ['0', '1', '2'])->comment('se ocorreram complicações');
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('imagiologias_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('imagiologias_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('imagiologias_deleted_by_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagiologias');
    }
};
