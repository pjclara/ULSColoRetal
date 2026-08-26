<?php

namespace App\Actions\{EstadoDaAlta}s;

use App\Models\EstadoDaAlta;

class DeleteEstadoDaAltaAction
{
    public function handle(EstadoDaAlta $model): void
    {
        $model->delete();
    }
}