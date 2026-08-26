<?php

namespace App\ViewModels;

use App\Models\Utente;

class UtenteViewModel
{
    public function __construct(
        protected Utente $model
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