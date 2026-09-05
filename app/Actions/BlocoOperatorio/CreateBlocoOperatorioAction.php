<?php

namespace App\Actions\{BlocoOperatorio}s;

use App\Models\BlocoOperatorio;

class CreateBlocoOperatorioAction
{
    public function handle(array $data): BlocoOperatorio
    {
        return BlocoOperatorio::create($data);
    }
}