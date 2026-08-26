<?php

namespace App\Actions\{EstadoDaAlta}s;

use App\Models\EstadoDaAlta;

class CreateEstadoDaAltaAction
{
    public function handle(array $data): EstadoDaAlta
    {
        return EstadoDaAlta::create($data);
    }
}