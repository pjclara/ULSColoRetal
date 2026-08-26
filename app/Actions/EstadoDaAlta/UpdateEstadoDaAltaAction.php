<?php

namespace App\Actions\{EstadoDaAlta}s;

use App\Models\EstadoDaAlta;

class UpdateEstadoDaAltaAction
{
    public function handle(EstadoDaAlta $model, array $data): EstadoDaAlta
    {
        $model->update($data);

        return $model->refresh();
    }
}