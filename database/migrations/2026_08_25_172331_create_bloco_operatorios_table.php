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
        Schema::create('bloco_operatorios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('internamento_id')->index('bloco_operatorios_internamento_id_foreign');
            $table->date('data_de_inicio');
            $table->unsignedBigInteger('tipo_de_cirurgia_id')->index('bloco_operatorios_tipo_de_cirurgia_id_foreign');
            $table->enum('resseccao_de_orgao', ['1', '2', '99'])->nullable()->default('99');
            $table->unsignedBigInteger('re_intervencao_nao_programada_id')->index('bloco_operatorios_re_intervencao_nao_programada_id_foreign');
            $table->longText('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('bloco_operatorios_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('bloco_operatorios_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('bloco_operatorios_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();
            $table->unsignedBigInteger('tipo_de_abordagem_id')->index('bloco_operatorios_tipo_de_abordagem_id_foreign');
            $table->string('causa_de_conversao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bloco_operatorios');
    }
};
