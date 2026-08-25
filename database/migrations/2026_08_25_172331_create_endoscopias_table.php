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
        Schema::create('endoscopias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referenciacao_id')->index('endoscopias_referenciacao_id_foreign');
            $table->date('data_colonoscopia')->comment('data da colonsocpia');
            $table->unsignedBigInteger('nivel_atingido_id')->index('endoscopias_nivel_atingido_id_foreign');
            $table->enum('local_colonoscopia', ['1', '2', '99'])->comment('local de realização');
            $table->enum('escala_boston', ['1', '2', '3', '99'])->nullable()->comment('qualidade da preparação');
            $table->enum('objectivo_colonoscopia', ['1', '2', '3', '4', '5', '6', '7', '8', '99'])->comment('objectivo de realização');
            $table->enum('tipo_colonoscopia', ['1', '2', '99'])->comment('tipo de realização');
            $table->enum('com_polipos', ['1', '2', '3', '4', '99'])->comment('se tem polipos');
            $table->enum('polipos_excisados_completo', ['1', '2', '3', '4', '99'])->nullable();
            $table->enum('com_alteracoes', ['1', '2', '3', '99'])->comment('se tem alterações');
            $table->enum('causa_nivel_atingido', ['1', '2', '98', '99'])->nullable()->comment('razão pelo qual a colonoscopia não é total');
            $table->boolean('bloquear_tabela')->default(false)->comment('Se dados estão bloqueados');
            $table->enum('complicacoes', ['1', '2', '3', '4', '99'])->comment('se ocorreram complicações');
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('endoscopias_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('endoscopias_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('endoscopias_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('endoscopias');
    }
};
