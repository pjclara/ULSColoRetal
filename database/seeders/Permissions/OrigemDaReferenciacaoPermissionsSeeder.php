<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class OrigemDaReferenciacaoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'origem-da-referenciacao.view',
            'origem-da-referenciacao.create',
            'origem-da-referenciacao.update',
            'origem-da-referenciacao.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}