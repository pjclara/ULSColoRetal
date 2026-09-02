<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AgendamentoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'agendamento.view',
            'agendamento.create',
            'agendamento.update',
            'agendamento.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}