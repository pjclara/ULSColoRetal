<?php

namespace App\ViewModels;

use App\Models\Agendamento;

class AgendamentoViewModel
{
    public function __construct(
        protected Agendamento $model
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