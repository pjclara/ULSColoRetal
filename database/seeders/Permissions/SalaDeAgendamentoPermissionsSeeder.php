<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SalaDeAgendamentoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'sala-de-agendamento.view',
            'sala-de-agendamento.create',
            'sala-de-agendamento.update',
            'sala-de-agendamento.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}