<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class CasoSocialPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'caso-social.view',
            'caso-social.create',
            'caso-social.update',
            'caso-social.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}