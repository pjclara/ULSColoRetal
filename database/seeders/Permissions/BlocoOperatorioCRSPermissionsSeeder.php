<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class BlocoOperatorioCRSPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'bloco-operatorio-c-r.view',
            'bloco-operatorio-c-r.create',
            'bloco-operatorio-c-r.update',
            'bloco-operatorio-c-r.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}