<?php

namespace App\Actions\{Internamento}s;

use App\Models\Internamento;

class UpdateInternamentoAction
{
    public function handle(Internamento $model, array $data): Internamento
    {
        $model->update($data);

        return $model->refresh();
    }
}