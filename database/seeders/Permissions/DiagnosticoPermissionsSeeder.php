<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class DiagnosticoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'diagnostico.view',
            'diagnostico.create',
            'diagnostico.update',
            'diagnostico.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}