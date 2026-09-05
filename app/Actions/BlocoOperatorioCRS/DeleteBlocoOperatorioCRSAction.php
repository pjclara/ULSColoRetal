<?php

namespace App\Actions\{BlocoOperatorioCRS}s;

use App\Models\BlocoOperatorioCRS;

class DeleteBlocoOperatorioCRSAction
{
    public function handle(BlocoOperatorioCRS $model): void
    {
        $model->delete();
    }
}