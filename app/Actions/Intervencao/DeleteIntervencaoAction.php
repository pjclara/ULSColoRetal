<?php

namespace App\Actions\{Intervencao}s;

use App\Models\Intervencao;

class DeleteIntervencaoAction
{
    public function handle(Intervencao $model): void
    {
        $model->delete();
    }
}