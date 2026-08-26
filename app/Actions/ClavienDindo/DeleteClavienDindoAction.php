<?php

namespace App\Actions\{ClavienDindo}s;

use App\Models\ClavienDindo;

class DeleteClavienDindoAction
{
    public function handle(ClavienDindo $model): void
    {
        $model->delete();
    }
}