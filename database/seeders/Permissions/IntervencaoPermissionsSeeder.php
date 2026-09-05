<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class IntervencaoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'intervencao.view',
            'intervencao.create',
            'intervencao.update',
            'intervencao.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}