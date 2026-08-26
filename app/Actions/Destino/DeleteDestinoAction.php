<?php

namespace App\Actions\{Destino}s;

use App\Models\Destino;

class DeleteDestinoAction
{
    public function handle(Destino $model): void
    {
        $model->delete();
    }
}