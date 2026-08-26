<?php

namespace App\Actions\{Localizacao}s;

use App\Models\Localizacao;

class DeleteLocalizacaoAction
{
    public function handle(Localizacao $model): void
    {
        $model->delete();
    }
}