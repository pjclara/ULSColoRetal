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
        ];
    }
}
