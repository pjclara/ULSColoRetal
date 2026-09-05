<?php

namespace App\Actions\{BlocoOperatorio}s;

use App\Models\BlocoOperatorio;

class DeleteBlocoOperatorioAction
{
    public function handle(BlocoOperatorio $model): void
    {
        $model->delete();
    }
}