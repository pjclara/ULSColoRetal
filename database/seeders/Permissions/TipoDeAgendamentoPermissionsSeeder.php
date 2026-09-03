<?php

namespace Database\Seeders\Permissions;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class TipoDeAgendamentoPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'tipo-de-agendamento.view',
            'tipo-de-agendamento.create',
            'tipo-de-agendamento.update',
            'tipo-de-agendamento.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        $adminRole->givePermissionTo($permissions);
    }
}