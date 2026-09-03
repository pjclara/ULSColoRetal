<?php

namespace App\ViewModels;

use App\Models\ListaDeEspera;
use JsonSerializable;

class ListaDeEsperaViewModel implements JsonSerializable
{
    public function __construct(
        protected readonly ListaDeEspera $model
    ) {}


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->model->id,
            'prioridade_id' => $this->model->prioridade_id,
            'data_de_lista' => $this->model->data_de_lista->format('Y-m-d'),
            'estado_lista_espera' => $this->model->estado_lista_espera,
            'cancelar_lista_espera' => $this->model->cancelar_lista_espera,
            'comentarios' => $this->model->comentarios,
            'responsavel' => $this->model->responsavel?->toArray(),
            'responsavel_id' => $this->model->responsavel_id,
            'diagnosticos' => $this->model->diagnosticos?->toArray(),
            'agendamentos' => $this->model->agendamentos?->map(fn ($agendamento) => [
                'id' => $agendamento->id,
                'lista_de_espera_id' => $agendamento->lista_de_espera_id,
                'start' => $agendamento->start,
                'end' => $agendamento->end,
                'responsavel_id' => $agendamento->responsavel_id,
                'tipo_de_agendamento_id' => $agendamento->tipo_de_agendamento_id,
                'local_de_agendamento_id' => $agendamento->local_de_agendamento_id,
                'sala_de_agendamento_id' => $agendamento->sala_de_agendamento_id,
                'periodo_de_agendamento_id' => $agendamento->periodo_de_agendamento_id,
                'estado_de_agendamento' => $agendamento->estado_de_agendamento,
                'estado_de_agendamento_id' => $agendamento->estado_de_agendamento_id,
                'comentarios' => $agendamento->comentarios,
            ])->values()->all() ?? [],
        ];
    }
}
