<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CentroDeReferenciaPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'centro-de-referencium.view',
            'centro-de-referencium.create',
            'centro-de-referencium.update',
            'centro-de-referencium.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}