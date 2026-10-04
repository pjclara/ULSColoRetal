<?php

namespace App\Observers;

use App\Models\BlocoOperatorio;
use App\Services\AgendamentoService;

/**
 * Mantém o estado dos agendamentos coerente com os blocos operatórios: um bloco no dia do
 * agendamento marca-o "Operado"; ao apagar o bloco volta a "Agendado".
 */
class BlocoOperatorioObserver
{
    public function __construct(private AgendamentoService $agendamentos) {}

    public function created(BlocoOperatorio $bloco): void
    {
        $this->agendamentos->marcarOperadosPorBloco($bloco);
    }

    public function updated(BlocoOperatorio $bloco): void
    {
        if (!$bloco->wasChanged(['data_de_inicio', 'internamento_id'])) {
            return;
        }

        // o bloco mudou de dia/doente: o agendamento antigo pode já não ter cirurgia
        $this->agendamentos->reverterOperadosPorBloco(
            $bloco->getOriginal('internamento_id'),
            $bloco->getOriginal('data_de_inicio'),
        );
        $this->agendamentos->marcarOperadosPorBloco($bloco);
    }

    public function deleted(BlocoOperatorio $bloco): void
    {
        $this->agendamentos->reverterOperadosPorBloco($bloco->internamento_id, $bloco->data_de_inicio);
    }

    public function restored(BlocoOperatorio $bloco): void
    {
        $this->agendamentos->marcarOperadosPorBloco($bloco);
    }
}
