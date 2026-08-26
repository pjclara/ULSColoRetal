<?php

namespace App\Actions\{CasoSocial}s;

use App\Models\CasoSocial;

class CreateCasoSocialAction
{
    public function handle(array $data): CasoSocial
    {
        return CasoSocial::create($data);
    }
}