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
        Schema::create('lista_de_esperas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('prioridade_id')->nullable()->default(99);
            $table->unsignedBigInteger('utente_id')->index('lista_de_esperas_utente_id_foreign');
            $table->date('data_de_lista');
            $table->enum('estado_lista_espera', ['1', '2', '3', '4'])->default('1');
            $table->enum('cancelar_lista_espera', ['1', '2', '3', '4', '5', '6', '7', '99'])->nullable();
            $table->longText('comentarios')->nullable();
            $table->unsignedBigInteger('responsavel_id')->index('lista_de_esperas_responsavel_id_foreign');
            $table->unsignedBigInteger('created_by_id')->nullable()->index('lista_de_esperas_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('lista_de_esperas_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('lista_de_esperas_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lista_de_esperas');
    }
};
