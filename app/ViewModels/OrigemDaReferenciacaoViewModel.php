<?php

namespace App\ViewModels;

use App\Models\OrigemDaReferenciacao;

class OrigemDaReferenciacaoViewModel
{
    public function __construct(
        protected OrigemDaReferenciacao $model
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