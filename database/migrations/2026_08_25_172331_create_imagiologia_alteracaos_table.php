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
        Schema::create('imagiologia_alteracaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('imagiologia_id')->index();
            $table->unsignedBigInteger('local_lesao_id')->index('imagiologia_alteracaos_local_lesao_id_foreign');
            $table->integer('distancia_ma')->nullable()->comment('distancia margem anal');
            $table->integer('extensao_longitudinal')->nullable()->comment('extensao_longitudinal');
            $table->enum('adenopatias_locoRegionais_visivel', ['1', '2', '3', '99'])->comment('se tem adenopatias');
            $table->enum('invasao_estruturas_adjacentes', ['1', '2', '3', '99'])->comment('se há invasão das estruturas adjacentes');
            $table->set('estruturas_adjacentes_invadidas', ['1', '2', '3', '4', '5', '6', '7', '8', '99'])->comment('se tem adenopatias');
            $table->integer('fmr')->nullable()->comment('fascia meso rectal');
            $table->integer('distancia_fmr')->nullable()->comment('distancia_fmr');
            $table->integer('ivem')->nullable()->comment('invasao venosa extramural');
            $table->boolean('deposito_tumorais')->nullable()->comment('deposito_tumorais mesoreto');
            $table->boolean('gg_extramesorecto')->nullable()->comment('gg_extramesorecto');
            $table->integer('T')->nullable()->comment('estadio T');
            $table->integer('N')->nullable()->comment('estadio N');
            $table->boolean('bloquear_tabela')->default(false)->comment('Se dados estão bloqueados');
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('imagiologia_alteracaos_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('imagiologia_alteracaos_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('imagiologia_alteracaos_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imagiologia_alteracaos');
    }
};
