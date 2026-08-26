<?php

namespace App\Actions\{Diagnostico}s;

use App\Models\Diagnostico;

class CreateDiagnosticoAction
{
    public function handle(array $data): Diagnostico
    {
        return Diagnostico::create($data);
    }
}