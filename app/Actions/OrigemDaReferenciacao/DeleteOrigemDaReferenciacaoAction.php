<?php

namespace App\Actions\{OrigemDaReferenciacao}s;

use App\Models\OrigemDaReferenciacao;

class DeleteOrigemDaReferenciacaoAction
{
    public function handle(OrigemDaReferenciacao $model): void
    {
        $model->delete();
    }
}