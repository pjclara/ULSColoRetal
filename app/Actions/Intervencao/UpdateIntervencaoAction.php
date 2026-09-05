<?php

namespace App\Actions\{Intervencao}s;

use App\Models\Intervencao;

class UpdateIntervencaoAction
{
    public function handle(Intervencao $model, array $data): Intervencao
    {
        $model->update($data);

        return $model->refresh();
    }
}