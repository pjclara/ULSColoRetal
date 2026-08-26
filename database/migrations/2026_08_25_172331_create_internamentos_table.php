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
        Schema::create('internamentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('utente_id')->constrained()->onDelete('cascade');
            $table->string('cama', 10);
            $table->foreignId('localocalizacao_id')->constrained()->onDelete('cascade');
            $table->date('data_de_entrada');
            $table->unsignedBigInteger('origem_do_internamento_id')->index('internamentos_origem_do_internamento_id_foreign');
            $table->date('data_de_alta')->nullable();
            $table->date('data_de_saida')->nullable();
            $table->unsignedBigInteger('estado_da_alta_id')->nullable()->default(1)->index('internamentos_estado_da_alta_id_foreign');
            $table->unsignedBigInteger('responsavel_id')->index('internamentos_responsavel_id_foreign');
            $table->string('motivo_internamento');
            $table->unsignedBigInteger('clavien_dindo_id')->nullable()->index('internamentos_clavien_dindo_id_foreign');
            $table->unsignedBigInteger('destino_id')->nullable()->index('internamentos_destino_id_foreign');
            $table->unsignedBigInteger('caso_social_id')->nullable()->index('internamentos_caso_social_id_foreign');
            $table->boolean('bloquear_tabela')->default(false)->comment('Se dados estão bloqueados');
            $table->longText('comentarios')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internamentos');
    }
};
