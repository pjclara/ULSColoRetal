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
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lista_de_espera_id')->index('agendamentos_lista_de_espera_id_foreign');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->unsignedBigInteger('responsavel_id')->index('agendamentos_responsavel_id_foreign');
            $table->unsignedBigInteger('tipo_de_agendamento_id')->index('agendamentos_tipo_de_agendamento_id_foreign');
            $table->unsignedBigInteger('local_de_agendamento_id')->index('agendamentos_local_de_agendamento_id_foreign');
            $table->unsignedBigInteger('sala_de_agendamento_id')->index('agendamentos_sala_de_agendamento_id_foreign');
            $table->unsignedBigInteger('periodo_de_agendamento_id')->index('agendamentos_periodo_de_agendamento_id_foreign');
            $table->enum('estado_de_agendamento', ['1', '2', '3', '4'])->default('2');
            $table->enum('motivo_cancelar_agendamento', ['1', '2', '3', '4', '5', '6', '7', '99'])->nullable();
            $table->unsignedBigInteger('estado_de_agendamento_id')->nullable()->index('agendamentos_estado_de_agendamento_id_foreign');
            $table->enum('teste_covid', ['1', '2', '3'])->nullable();
            $table->enum('reserva_sangue', ['1', '2', '3'])->nullable();
            $table->longText('comentarios')->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable()->index('agendamentos_created_by_id_foreign');
            $table->unsignedBigInteger('updated_by_id')->nullable()->index('agendamentos_updated_by_id_foreign');
            $table->unsignedBigInteger('deleted_by_id')->nullable()->index('agendamentos_deleted_by_id_foreign');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
