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
        //
        $roleStandard = Role::findByName('standard');
        $rolePanelUser = Role::findByName('panel_user');

        $permissionViewAnyFile = Permission::findByName('view_any_file');
        $permissionCreateFile = Permission::findByName('create_file');

        $permissionViewAnyRecord = Permission::findByName('view_any_record');
        $permissionCreateRecord = Permission::findByName('create_record');

        $roleStandard->givePermissionTo($permissionViewAnyFile);
        $roleStandard->givePermissionTo($permissionCreateFile);
        $roleStandard->givePermissionTo($permissionViewAnyRecord);
        $roleStandard->givePermissionTo($permissionCreateRecord);

        $rolePanelUser->givePermissionTo($permissionViewAnyRecord);
    }
}
