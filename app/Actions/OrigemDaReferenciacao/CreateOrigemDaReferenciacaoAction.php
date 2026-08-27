<?php

namespace App\Actions\{OrigemDaReferenciacao}s;

use App\Models\OrigemDaReferenciacao;

class CreateOrigemDaReferenciacaoAction
{
    public function handle(array $data): OrigemDaReferenciacao
    {
        return OrigemDaReferenciacao::create($data);
    }
}