<?php

namespace App\Actions\{Internamento}s;

use App\Models\Internamento;

class DeleteInternamentoAction
{
    public function handle(Internamento $model): void
    {
        $model->delete();
    }
}