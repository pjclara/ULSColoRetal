<?php

namespace App\Actions\{CentroDeReferencia}s;

use App\Models\CentroDeReferencia;

class CreateCentroDeReferenciaAction
{
    public function handle(array $data): CentroDeReferencia
    {
        return CentroDeReferencia::create($data);
    }
}