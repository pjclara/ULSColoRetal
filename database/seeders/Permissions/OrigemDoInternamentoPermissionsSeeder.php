<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class OrigemDoInternamentoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'origem-do-internamento.view',
            'origem-do-internamento.create',
            'origem-do-internamento.update',
            'origem-do-internamento.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}