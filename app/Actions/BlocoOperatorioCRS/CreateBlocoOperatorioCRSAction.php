<?php

namespace App\Actions\{BlocoOperatorioCRS}s;

use App\Models\BlocoOperatorioCRS;

class CreateBlocoOperatorioCRSAction
{
    public function handle(array $data): BlocoOperatorioCRS
    {
        return BlocoOperatorioCRS::create($data);
    }
}