<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles

        // Role::truncate();
        // User::truncate();

        $superAdminRole = Role::create(['name' => 'super_admin']);
        $adminRole = Role::create(['name' => 'admin']);
        $standardRole = Role::create(['name' => 'standard']);
        $basicRole = Role::create(['name' => 'panel_user']);

        $superAdmin = new User;
        $superAdmin->name = 'Administrador Sayan';
        $superAdmin->email = 'doc@superadmin.com';
        $superAdmin->password = bcrypt('doc@superadmin.com');
        $superAdmin->save();

        $superAdmin->assignRole($superAdminRole);

        $admin = new User;
        $admin->name = 'Administrador';
        $admin->email = 'doc@admin.com';
        $admin->password = bcrypt('doc@admin.com');
        $admin->save();

        $admin->assignRole($adminRole);

        $standardOne = new User;
        $standardOne->name = 'Estandard One';
        $standardOne->email = 'doc@standardone.com';
        $standardOne->password = bcrypt('doc@standardone.com');
        $standardOne->save();

        $standardOne->assignRole($standardRole);

        $standardTwo = new User;
        $standardTwo->name = 'Estandard Two';
        $standardTwo->email = 'doc@standardtwo.com';
        $standardTwo->password = bcrypt('doc@standardtwo.com');
        $standardTwo->save();

        $standardTwo->assignRole($standardRole);

        $basic = new User;
        $basic->name = 'Usuario';
        $basic->email = 'doc@basic.com';
        $basic->password = bcrypt('doc@basic.com');
        $basic->save();

        $basic->assignRole($basicRole);

    }
}
