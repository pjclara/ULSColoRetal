<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'profiles.view',
            'profiles.update',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $admin = Role::findOrCreate('admin');
        $manager = Role::findOrCreate('manager');
        $user = Role::findOrCreate('user');

        $admin->syncPermissions($permissions);

        $manager->syncPermissions([
            'users.view',
            'users.update',
            'profiles.view',
            'profiles.update',
        ]);

        $user->syncPermissions([
            'profiles.view',
            'profiles.update',
        ]);
    }
}
