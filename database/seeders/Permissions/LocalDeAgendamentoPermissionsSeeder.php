<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class LocalDeAgendamentoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'local-de-agendamento.view',
            'local-de-agendamento.create',
            'local-de-agendamento.update',
            'local-de-agendamento.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}