<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class BlocoOperatorioPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'bloco-operatorio.view',
            'bloco-operatorio.create',
            'bloco-operatorio.update',
            'bloco-operatorio.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}