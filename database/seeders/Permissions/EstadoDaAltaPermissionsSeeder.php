<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class EstadoDaAltaPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'estado-da-altum.view',
            'estado-da-altum.create',
            'estado-da-altum.update',
            'estado-da-altum.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}