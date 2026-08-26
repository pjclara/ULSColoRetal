<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ClavienDindoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'clavien-dindo.view',
            'clavien-dindo.create',
            'clavien-dindo.update',
            'clavien-dindo.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}