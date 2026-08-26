<?php

namespace App\Actions\{Localizacao}s;

use App\Models\Localizacao;

class CreateLocalizacaoAction
{
    public function handle(array $data): Localizacao
    {
        return Localizacao::create($data);
    }
}