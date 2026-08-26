<?php

namespace App\Actions\{Localizacao}s;

use App\Models\Localizacao;

class UpdateLocalizacaoAction
{
    public function handle(Localizacao $model, array $data): Localizacao
    {
        $model->update($data);

        return $model->refresh();
    }
}