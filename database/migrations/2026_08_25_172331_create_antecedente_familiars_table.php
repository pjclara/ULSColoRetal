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
        Schema::create('antecedente_familiars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referenciacao_id')->index();
            $table->enum('parente_1_grau_neoplasia', ['1', '2', '3', '99'])->comment('se parente 1º grau com neoplasia');
            $table->enum('parente_1_grau_neoplasia_CCR', ['1', '2', '3', '99'])->nullable()->comment('se parente 1º grau com neoplasia CCR');
            $table->integer('idade_mais_novo_caso_parente_1_grau')->nullable()->comment('idade do caso mais novo parente 1 grau');
            $table->enum('parente_2_grau_neoplasia', ['1', '2', '3', '99'])->comment('se parente 2º grau com neoplasia');
            $table->enum('parente_2_grau_neoplasia_CCR', ['1', '2', '3', '99'])->nullable()->comment('se parente 2º grau com neoplasia CCR');
            $table->integer('idade_mais_novo_caso_parente_2_grau')->nullable()->comment('idade do caso mais novo parente 2 grau');
            $table->boolean('bloquear_tabela')->default(false)->comment('Se dados estão bloqueados');
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('antecedente_familiars_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('antecedente_familiars_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('antecedente_familiars_deleted_by_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antecedente_familiars');
    }
};
