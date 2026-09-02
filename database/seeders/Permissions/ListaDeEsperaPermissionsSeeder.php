<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ListaDeEsperaPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'lista-de-espera.view',
            'lista-de-espera.create',
            'lista-de-espera.update',
            'lista-de-espera.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}