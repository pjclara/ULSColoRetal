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
        Schema::create('antecedente_fisiologicos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('referenciacao_id')->index();
            $table->integer('peso')->nullable()->comment('peso do utente em kg');
            $table->enum('perda_recente_peso', ['1', '2', '97', '98'])->comment('perda de peso recente > 10%');
            $table->integer('altura')->nullable()->comment('altura do utente');
            $table->decimal('imc', 5, 1)->nullable()->comment('IMC do utente');
            $table->integer('ecog')->nullable();
            $table->enum('fumador', ['1', '2', '3', '4', '99'])->comment('se fuma, se sim a mais ou menos de 6 meses que deixou de fumar');
            $table->enum('alcool', ['1', '2', '3', '4', '99'])->comment('se ingere alcool, se sim a mais ou menos de 6 meses que deixou de fumar');
            $table->enum('alergias', ['1', '2', '3', '99'])->comment('se tem alergias');
            $table->mediumText('alergias_quais')->nullable()->comment('quais as alergias');
            $table->enum('colonoscopia_previa', ['1', '2', '3', '99'])->comment('se realizou colonoscopias previamente');
            $table->enum('hemostase', ['1', '2', '3', '99'])->nullable()->comment('se tem equimoses / hemorragia com facilidade');
            $table->enum('transito_intestinal', ['1', '2', '3', '4', '99'])->nullable()->comment('ferquência de trânsito intestinal');
            $table->enum('adenomas_colonoscopia_previa', ['1', '2', '3', '99'])->nullable()->comment('se apresentava adenomas em colonoscopias previas');
            $table->boolean('bloquear_tabela')->default(false)->comment('Se dados estão bloqueados');
            $table->text('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('antecedente_fisiologicos_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('antecedente_fisiologicos_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('antecedente_fisiologicos_deleted_by_id_foreign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antecedente_fisiologicos');
    }
};
