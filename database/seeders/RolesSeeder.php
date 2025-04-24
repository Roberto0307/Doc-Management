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

        $adminRole = Role::create(['name' => 'super_admin']);
        $standardRole = Role::create(['name' => 'standard']);
        $basicRole = Role::create(['name' => 'panel_user']);

        $admin = new User;
        $admin->name = 'Administrador';
        $admin->email = 'doc@admin.com';
        $admin->password = bcrypt('doc@admin.com');
        $admin->save();

        $admin->assignRole($adminRole);

        $standardOne = new User;
        $standardOne->name = 'Profesional';
        $standardOne->email = 'doc@pro.com';
        $standardOne->password = bcrypt('doc@pro.com');
        $standardOne->save();

        $standardOne->assignRole($standardRole);

        $standardTwo = new User;
        $standardTwo->name = 'General';
        $standardTwo->email = 'doc@standard.com';
        $standardTwo->password = bcrypt('doc@standard.com');
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
