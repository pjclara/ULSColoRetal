<?php

namespace App\Actions\{CasoSocial}s;

use App\Models\CasoSocial;

class UpdateCasoSocialAction
{
    public function handle(CasoSocial $model, array $data): CasoSocial
    {
        $model->update($data);

        return $model->refresh();
    }
}