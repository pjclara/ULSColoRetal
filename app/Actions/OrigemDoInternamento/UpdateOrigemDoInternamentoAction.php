<?php

namespace App\Actions\{OrigemDoInternamento}s;

use App\Models\OrigemDoInternamento;

class UpdateOrigemDoInternamentoAction
{
    public function handle(OrigemDoInternamento $model, array $data): OrigemDoInternamento
    {
        $model->update($data);

        return $model->refresh();
    }
}