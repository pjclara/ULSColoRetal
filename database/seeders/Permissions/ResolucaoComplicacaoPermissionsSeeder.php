<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class ResolucaoComplicacaoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'resolucao-complicacao.view',
            'resolucao-complicacao.create',
            'resolucao-complicacao.update',
            'resolucao-complicacao.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}
