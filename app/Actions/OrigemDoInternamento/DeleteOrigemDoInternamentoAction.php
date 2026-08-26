<?php

namespace App\Actions\{OrigemDoInternamento}s;

use App\Models\OrigemDoInternamento;

class DeleteOrigemDoInternamentoAction
{
    public function handle(OrigemDoInternamento $model): void
    {
        $model->delete();
    }
}