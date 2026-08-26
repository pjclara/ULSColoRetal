<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class InternamentoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'internamento.view',
            'internamento.create',
            'internamento.update',
            'internamento.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}