<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        $roleAdmin = Role::findByName('admin');
        $roleStandard = Role::findByName('standard');
        $rolePanelUser = Role::findByName('panel_user');

        // Permisos
        $permissionViewAnyFile = Permission::findByName('view_any_file');
        $permissionCreateFile = Permission::findByName('create_file');

        $permissionViewAnyRecord = Permission::findByName('view_any_record');
        $permissionCreateRecord = Permission::findByName('create_record');

        $permissionViewAnyProcess = Permission::findByName('view_any_process');
        $permissionCreateProcess = Permission::findByName('create_process');
        $permissionUpdateProcess = Permission::findByName('update_process');

        $permissionViewAnySubProcess = Permission::findByName('view_any_sub::process');
        $permissionCreateSubProcess = Permission::findByName('create_sub::process');
        $permissionUpdateSubProcess = Permission::findByName('update_sub::process');

        $permissionViewAnyType = Permission::findByName('view_any_type');
        $permissionCreateType = Permission::findByName('create_type');
        $permissionUpdateType = Permission::findByName('update_type');

        $permissionViewAnyStatus = Permission::findByName('view_any_status');
        $permissionUpdateStatus = Permission::findByName('update_status');

        $permissionViewAnyUser = Permission::findByName('view_any_user');
        $permissionCreateUser = Permission::findByName('create_user');
        $permissionUpdateUser = Permission::findByName('update_user');

        // Permisos Admin
        $roleAdmin->givePermissionTo($permissionViewAnyFile);
        $roleAdmin->givePermissionTo($permissionCreateFile);

        $roleAdmin->givePermissionTo($permissionViewAnyRecord);
        $roleAdmin->givePermissionTo($permissionCreateRecord);

        $roleAdmin->givePermissionTo($permissionViewAnyProcess);
        $roleAdmin->givePermissionTo($permissionCreateProcess);
        $roleAdmin->givePermissionTo($permissionUpdateProcess);

        $roleAdmin->givePermissionTo($permissionViewAnySubProcess);
        $roleAdmin->givePermissionTo($permissionCreateSubProcess);
        $roleAdmin->givePermissionTo($permissionUpdateSubProcess);

        $roleAdmin->givePermissionTo($permissionViewAnyType);
        $roleAdmin->givePermissionTo($permissionCreateType);
        $roleAdmin->givePermissionTo($permissionUpdateType);

        $roleAdmin->givePermissionTo($permissionViewAnyStatus);
        $roleAdmin->givePermissionTo($permissionUpdateStatus);

        $roleAdmin->givePermissionTo($permissionViewAnyUser);
        $roleAdmin->givePermissionTo($permissionCreateUser);
        $roleAdmin->givePermissionTo($permissionUpdateUser);

        // Permisos Standard
        $roleStandard->givePermissionTo($permissionViewAnyFile);
        $roleStandard->givePermissionTo($permissionCreateFile);
        $roleStandard->givePermissionTo($permissionViewAnyRecord);
        $roleStandard->givePermissionTo($permissionCreateRecord);

        // Permisos Panel User
        $rolePanelUser->givePermissionTo($permissionViewAnyRecord);
    }
}
