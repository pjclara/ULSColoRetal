<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UtentePermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'utente.view',
            'utente.create',
            'utente.update',
            'utente.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}