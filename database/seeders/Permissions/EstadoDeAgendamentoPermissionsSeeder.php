<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class EstadoDeAgendamentoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'estado-de-agendamento.view',
            'estado-de-agendamento.create',
            'estado-de-agendamento.update',
            'estado-de-agendamento.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}