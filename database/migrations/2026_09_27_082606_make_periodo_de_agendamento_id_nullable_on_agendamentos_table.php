<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // O "Período de agendamento" deixou de ser pedido no formulário de agendamento.
        DB::statement('ALTER TABLE agendamentos MODIFY periodo_de_agendamento_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE agendamentos MODIFY periodo_de_agendamento_id BIGINT UNSIGNED NOT NULL');
    }
};
