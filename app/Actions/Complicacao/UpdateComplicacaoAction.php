<?php

namespace App\Actions\{Complicacao}s;

use App\Models\Complicacao;

class UpdateComplicacaoAction
{
    public function handle(Complicacao $model, array $data): Complicacao
    {
        $model->update($data);

        return $model->refresh();
    }
}