<?php

namespace App\Actions\{Internamento}s;

use App\Models\Internamento;

class CreateInternamentoAction
{
    public function handle(array $data): Internamento
    {
        return Internamento::create($data);
    }
}