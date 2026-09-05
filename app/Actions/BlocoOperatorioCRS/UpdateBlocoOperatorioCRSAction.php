<?php

namespace App\Actions\{BlocoOperatorioCRS}s;

use App\Models\BlocoOperatorioCRS;

class UpdateBlocoOperatorioCRSAction
{
    public function handle(BlocoOperatorioCRS $model, array $data): BlocoOperatorioCRS
    {
        $model->update($data);

        return $model->refresh();
    }
}