<?php

namespace App\Actions\{Destino}s;

use App\Models\Destino;

class CreateDestinoAction
{
    public function handle(array $data): Destino
    {
        return Destino::create($data);
    }
}