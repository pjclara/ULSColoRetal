<?php

namespace App\Actions\{OrigemDoInternamento}s;

use App\Models\OrigemDoInternamento;

class CreateOrigemDoInternamentoAction
{
    public function handle(array $data): OrigemDoInternamento
    {
        return OrigemDoInternamento::create($data);
    }
}