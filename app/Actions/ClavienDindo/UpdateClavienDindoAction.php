<?php

namespace App\Actions\{ClavienDindo}s;

use App\Models\ClavienDindo;

class UpdateClavienDindoAction
{
    public function handle(ClavienDindo $model, array $data): ClavienDindo
    {
        $model->update($data);

        return $model->refresh();
    }
}