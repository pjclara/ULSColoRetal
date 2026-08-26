<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class LocalizacaoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'localizacao.view',
            'localizacao.create',
            'localizacao.update',
            'localizacao.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}