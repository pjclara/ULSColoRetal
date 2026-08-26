<?php

namespace App\Actions\{ClavienDindo}s;

use App\Models\ClavienDindo;

class CreateClavienDindoAction
{
    public function handle(array $data): ClavienDindo
    {
        return ClavienDindo::create($data);
    }
}