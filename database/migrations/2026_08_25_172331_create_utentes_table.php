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
        Schema::create('utentes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('slug');
            $table->integer('numero_utente')->nullable();
            $table->integer('numero_processo');
            $table->unsignedBigInteger('sexo_id')->index('utentes_sexo_id_foreign');
            $table->unsignedBigInteger('estado_civil_id')->nullable()->index('utentes_estado_civil_id_foreign');
            $table->unsignedBigInteger('etnia_id')->nullable()->index('utentes_etnia_id_foreign');
            $table->unsignedBigInteger('concelho_id')->index('utentes_concelho_id_foreign');
            $table->date('data_nascimento');
            $table->unsignedBigInteger('classe_social_id')->nullable()->index('utentes_classe_social_id_foreign');
            $table->unsignedBigInteger('created_by_id')->nullable()->index('utentes_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('utentes_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('utentes_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utentes');
    }
};
