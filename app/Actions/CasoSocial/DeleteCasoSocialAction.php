<?php

namespace App\Actions\{CasoSocial}s;

use App\Models\CasoSocial;

class DeleteCasoSocialAction
{
    public function handle(CasoSocial $model): void
    {
        $model->delete();
    }
}