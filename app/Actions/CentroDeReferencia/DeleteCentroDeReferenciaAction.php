<?php

namespace App\Actions\{CentroDeReferencia}s;

use App\Models\CentroDeReferencia;

class DeleteCentroDeReferenciaAction
{
    public function handle(CentroDeReferencia $model): void
    {
        $model->delete();
    }
}