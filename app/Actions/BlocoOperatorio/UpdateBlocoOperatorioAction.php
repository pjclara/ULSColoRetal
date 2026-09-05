<?php

namespace App\Actions\{BlocoOperatorio}s;

use App\Models\BlocoOperatorio;

class UpdateBlocoOperatorioAction
{
    public function handle(BlocoOperatorio $model, array $data): BlocoOperatorio
    {
        $model->update($data);

        return $model->refresh();
    }
}