<?php

namespace App\Actions\{Intervencao}s;

use App\Models\Intervencao;

class CreateIntervencaoAction
{
    public function handle(array $data): Intervencao
    {
        return Intervencao::create($data);
    }
}