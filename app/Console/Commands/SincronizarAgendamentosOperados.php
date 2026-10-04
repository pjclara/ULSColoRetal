<?php

namespace App\Console\Commands;

use App\Services\AgendamentoService;
use Illuminate\Console\Command;

class SincronizarAgendamentosOperados extends Command
{
    protected $signature = 'agendamentos:sincronizar-operados';

    protected $description = 'Marca como "Operado" os agendamentos com bloco operatório do mesmo utente no mesmo dia';

    public function handle(AgendamentoService $service): int
    {
        $total = $service->sincronizarOperadosComBlocos();

        $this->info("{$total} agendamento(s) marcado(s) como operado.");

        return self::SUCCESS;
    }
}
