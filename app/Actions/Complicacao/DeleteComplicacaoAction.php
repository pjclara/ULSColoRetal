<?php

namespace App\Actions\{Complicacao}s;

use App\Models\Complicacao;

class DeleteComplicacaoAction
{
    public function handle(Complicacao $model): void
    {
        $model->delete();
    }
}