<?php

namespace App\ViewModels;

use App\Models\EstadoDeAgendamento;

class EstadoDeAgendamentoViewModel
{
    public function __construct(
        protected EstadoDeAgendamento $model
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->model->id,
            'created_at' => $this->model->created_at?->toISOString(),
            'updated_at' => $this->model->updated_at?->toISOString(),
        ];
    }
}