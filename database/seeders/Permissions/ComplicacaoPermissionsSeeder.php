<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ComplicacaoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'complicacao.view',
            'complicacao.create',
            'complicacao.update',
            'complicacao.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}