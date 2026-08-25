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
        Schema::create('intervencao_descricaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('intervencao_id')->index('intervencao_descricaos_intervencao_id_foreign');
            $table->unsignedBigInteger('anastemose_modo_id')->index('intervencao_descricaos_anastemose_modo_id_foreign');
            $table->unsignedBigInteger('anastemose_via_id')->index('intervencao_descricaos_anastemose_via_id_foreign');
            $table->unsignedBigInteger('anastemose_sentido_id')->index('intervencao_descricaos_anastemose_sentido_id_foreign');
            $table->unsignedBigInteger('tipo_de_reconstrucao_id')->index('intervencao_descricaos_tipo_de_reconstrucao_id_foreign');
            $table->enum('reforco_anastemose', ['1', '2', '99']);
            $table->unsignedBigInteger('localizacao_anastemose_id')->index('intervencao_descricaos_localizacao_anastemose_id_foreign');
            $table->unsignedBigInteger('confirmacao_anastemose_id')->index('intervencao_descricaos_confirmacao_anastemose_id_foreign');
            $table->enum('libertacao_angulo', ['1', '2', '99']);
            $table->integer('numero_cargas')->nullable();
            $table->integer('distancia_linha_pectinea_anastemose')->nullable();
            $table->integer('distancia_linha_pectinea_tumor')->nullable();
            $table->unsignedBigInteger('qualidade_peca_operatoria_id')->nullable()->index('intervencao_descricaos_qualidade_peca_operatoria_id_foreign');
            $table->longText('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->index('intervencao_descricaos_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->index('intervencao_descricaos_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('intervencao_descricaos_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intervencao_descricaos');
    }
};
