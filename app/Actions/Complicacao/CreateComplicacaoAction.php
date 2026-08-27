<?php

namespace App\Actions\{Complicacao}s;

use App\Models\Complicacao;

class CreateComplicacaoAction
{
    public function handle(array $data): Complicacao
    {
        return Complicacao::create($data);
    }
}