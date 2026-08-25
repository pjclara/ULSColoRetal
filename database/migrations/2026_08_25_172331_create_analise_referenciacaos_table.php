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
        Schema::create('analise_referenciacaos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referenciacao_id');
            $table->date('data_analise');
            $table->enum('timming_id', ['1', '2', '3', '99']);
            $table->boolean('bloquear_tabela')->default(false)->comment('Se dados estão bloqueados');
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('analise_referenciacaos_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('analise_referenciacaos_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('analise_referenciacaos_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['referenciacao_id', 'data_analise']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analise_referenciacaos');
    }
};
