<?php

namespace App\Actions\{OrigemDaReferenciacao}s;

use App\Models\OrigemDaReferenciacao;

class UpdateOrigemDaReferenciacaoAction
{
    public function handle(OrigemDaReferenciacao $model, array $data): OrigemDaReferenciacao
    {
        $model->update($data);

        return $model->refresh();
    }
}