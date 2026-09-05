<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class IntervencaoDescricaoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'intervencao-descricao.create',
            'intervencao-descricao.update',
            'intervencao-descricao.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}
