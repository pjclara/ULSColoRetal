<?php

namespace App\ViewModels;

use App\Models\CentroDeReferencia;

class CentroDeReferenciaViewModel
{
    public function __construct(
        protected CentroDeReferencia $model
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->model->id,
            'data_de_diagnostico' => $this->model->data_de_diagnostico,
            'data_de_referenciacao' => $this->model->data_de_referenciacao,
            'origem_id' => $this->model->origem_id,
            'data_de_entrada' => $this->model->data_de_entrada,
            'data_de_saida' => $this->model->data_de_saida,
            'destino_id' => $this->model->destino_id,
            'responsavel_id' => $this->model->responsavel_id,
            'comentarios' => $this->model->comentarios,
            'created_at' => $this->model->created_at?->toISOString(),
            'updated_at' => $this->model->updated_at?->toISOString(),
        ];
    }
}