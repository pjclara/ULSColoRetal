<?php

namespace App\Actions\{Destino}s;

use App\Models\Destino;

class UpdateDestinoAction
{
    public function handle(Destino $model, array $data): Destino
    {
        $model->update($data);

        return $model->refresh();
    }
}