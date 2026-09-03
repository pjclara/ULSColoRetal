<?php

namespace App\ViewModels;

use App\Models\TipoDeAgendamento;

class TipoDeAgendamentoViewModel
{
    public function __construct(
        protected TipoDeAgendamento $model
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