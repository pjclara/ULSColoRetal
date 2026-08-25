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
        Schema::create('referenciacao_centro_de_referencias', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('uuid', 36)->comment('(DC2Type:guid)');
            $table->unsignedBigInteger('utente_id')->index('referenciacao_centro_de_referencias_utente_id_foreign');
            $table->date('data_de_diagnostico')->nullable();
            $table->date('data_de_referenciacao');
            $table->unsignedBigInteger('origem_id')->index('referenciacao_centro_de_referencias_origem_id_foreign');
            $table->date('data_de_entrada')->nullable();
            $table->date('data_de_saida')->nullable();
            $table->unsignedBigInteger('destino_id')->nullable()->index('referenciacao_centro_de_referencias_destino_id_foreign');
            $table->unsignedBigInteger('responsavel_id')->index('referenciacao_centro_de_referencias_responsavel_id_foreign');
            $table->longText('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->index('referenciacao_centro_de_referencias_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('referenciacao_centro_de_referencias_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('referenciacao_centro_de_referencias_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referenciacao_centro_de_referencias');
    }
};
