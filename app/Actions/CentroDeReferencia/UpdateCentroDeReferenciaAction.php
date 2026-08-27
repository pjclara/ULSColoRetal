<?php

namespace App\Actions\{CentroDeReferencia}s;

use App\Models\CentroDeReferencia;

class UpdateCentroDeReferenciaAction
{
    public function handle(CentroDeReferencia $model, array $data): CentroDeReferencia
    {
        $model->update($data);

        return $model->refresh();
    }
}