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
        Schema::create('seguimentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referenciacao_id')->index('seguimentos_referenciacao_id_foreign');
            $table->date('data')->comment('data da avaliação do utente');
            $table->enum('evolucao_utente', ['1', '2', '3', '4'])->comment('evolução do utente desde a ultima consulta');
            $table->enum('local_avaliacao', ['1', '2', '3', '4', '5'])->comment('local de avaliação do utente');
            $table->set('locais_seguimento', ['1', '2', '3', '4', '5'])->comment('locais de seguimento do utente');
            $table->boolean('recidiva')->comment('se tem recidiva');
            $table->date('data_recidiva')->nullable()->comment('data da avaliação do utente');
            $table->boolean('metasteses')->comment('se tem sinais de metastização');
            $table->date('data_metasteses')->nullable()->comment('data da avaliação do utente');
            $table->boolean('doenca_metacrona')->comment('se tem doenca_metacrona');
            $table->date('data_doenca_metacrona')->nullable()->comment('data da avaliação do utente');
            $table->boolean('falecido')->comment('se faleceu');
            $table->date('data_falecimento')->nullable()->comment('data da avaliação do utente');
            $table->boolean('bloquear_tabela')->nullable()->default(false)->comment('Se dados estão bloqueados');
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('seguimentos_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('seguimentos_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('seguimentos_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimentos');
    }
};
