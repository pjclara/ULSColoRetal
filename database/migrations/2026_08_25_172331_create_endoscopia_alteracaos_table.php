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
        Schema::create('endoscopia_alteracaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('endoscopia_id')->index();
            $table->unsignedBigInteger('local_anatomico_id')->index('endoscopia_alteracaos_local_anatomico_id_foreign');
            $table->unsignedBigInteger('histologia_id')->index('endoscopia_alteracaos_histologia_id_foreign');
            $table->enum('tipo_alteracao', ['1', '2', '3', '4', '5', '99'])->nullable()->comment('tipo de alteração');
            $table->enum('tipo_intervencao', ['1', '2', '3', '97', '98', '99'])->nullable()->comment('tipo de intervenção');
            $table->integer('tamanho')->nullable()->comment('tamanho da lesão');
            $table->enum('estenosante', ['1', '2', '3', '99'])->nullable()->comment('se lesão estenosante');
            $table->enum('tatuado', ['1', '2', '3', '99'])->comment('tatuagem da lesão');
            $table->integer('distancia_linha_pectinea')->nullable()->comment('distancia à linha pectinea da lesão');
            $table->boolean('bloquear_tabela')->default(false)->comment('Se dados estão bloqueados');
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('endoscopia_alteracaos_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('endoscopia_alteracaos_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('endoscopia_alteracaos_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('endoscopia_alteracaos');
    }
};
