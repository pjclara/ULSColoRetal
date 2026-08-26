<?php

namespace App\ViewModels;

use App\Models\Localizacao;

class LocalizacaoViewModel
{
    public function __construct(
        protected Localizacao $model
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