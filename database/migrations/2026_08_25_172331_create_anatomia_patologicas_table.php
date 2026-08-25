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
        Schema::create('anatomia_patologicas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('intervencao_descricao_id')->index('anatomia_patologicas_intervencao_descricao_id_foreign');
            $table->unsignedBigInteger('peca_operatoria_id')->nullable()->index('anatomia_patologicas_peca_operatoria_id_foreign');
            $table->date('data_entrada')->nullable();
            $table->date('data_saida')->nullable();
            $table->integer('diametro')->nullable();
            $table->boolean('envolvimento_margem_circunferencial')->nullable();
            $table->integer('distancia_margem_circunferencial')->nullable();
            $table->integer('distancia_margem_proximal')->nullable();
            $table->integer('distancia_margem_distal')->nullable();
            $table->integer('gg_total')->nullable();
            $table->integer('gg_total_positivos')->nullable();
            $table->enum('anel_sinete', ['1', '2', '3', '99'])->default('99');
            $table->enum('budding_tumoral', ['1', '2', '3', '99'])->default('99');
            $table->enum('invasao_linfovascular', ['1', '2', '3', '99'])->default('99');
            $table->enum('invasao_neuronal', ['1', '2', '3', '99'])->default('99');
            $table->enum('invasao_extramural', ['1', '2', '3', '99'])->default('99');
            $table->enum('perfuracao_tumoral', ['1', '2', '3', '99'])->default('99');
            $table->enum('envolvimento_peritoneal', ['1', '2', '3', '99'])->default('99');
            $table->enum('TME_completa', ['1', '2', '3', '99'])->nullable()->default('99');
            $table->integer('tamanho_mesorecto')->nullable();
            $table->enum('adenoma_contiguo', ['1', '2', '3', '99'])->default('99');
            $table->enum('adenoma_satelite', ['1', '2', '3', '99'])->nullable();
            $table->enum('ressecao_r0', ['1', '2', '3', '99'])->default('99');
            $table->enum('msi', ['1', '2', '3', '99'])->nullable()->default('99');
            $table->enum('BRAF', ['1', '2', '3', '99'])->nullable()->default('99');
            $table->enum('hipometilacao', ['1', '2', '3', '99'])->nullable()->default('99');
            $table->enum('kras', ['1', '2', '3', '99'])->nullable()->default('99');
            $table->unsignedBigInteger('histologia_id')->nullable()->index('anatomia_patologicas_histologia_id_foreign');
            $table->enum('diferenciacao', ['1', '2', '3', '4', '5', '99'])->default('99');
            $table->enum('mucinoso', ['1', '2', '3', '99'])->default('99');
            $table->enum('pT', ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '99'])->default('99');
            $table->enum('pN', ['1', '2', '3', '4', '5', '6', '7', '8', '9', '99'])->default('99');
            $table->enum('pM', ['1', '2', '3', '4', '99'])->default('99');
            $table->longText('comentarios')->nullable();
            $table->boolean('bloquear_tabela')->default(false);
            $table->unsignedBigInteger('created_by_id')->nullable()->index('anatomia_patologicas_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('anatomia_patologicas_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('anatomia_patologicas_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anatomia_patologicas');
    }
};
