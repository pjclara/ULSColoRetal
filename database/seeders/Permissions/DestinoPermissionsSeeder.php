<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DestinoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'destino.view',
            'destino.create',
            'destino.update',
            'destino.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}