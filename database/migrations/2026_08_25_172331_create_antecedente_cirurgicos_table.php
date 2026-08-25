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
        Schema::create('antecedente_cirurgicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referenciacao_id')->index();
            $table->set('cirurgias_anteriores', ['1', '2', '3', '4', '5', '6', '7', '99', '8', '9', '10'])->comment('tipo de cirurgia anterior');
            $table->enum('data_ultima_cirurgia', ['1', '2', '3', '4', '99'])->nullable()->comment('data da ultima cirurgia');
            $table->enum('abordagem', ['1', '2', '99'])->nullable()->comment('tipo de abordagem cirurgica');
            $table->enum('tipo', ['1', '2', '3', '99'])->nullable()->comment('tipo de cirurgica');
            $table->boolean('bloquear_tabela')->default(false)->comment('Se dados estão bloqueados');
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('antecedente_cirurgicos_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('antecedente_cirurgicos_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('antecedente_cirurgicos_deleted_by_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antecedente_cirurgicos');
    }
};
