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
        /* $proRole = Role::create(['name' => 'pro']); */
        $standardRole = Role::create(['name' => 'standard']);
        $basicRole = Role::create(['name' => 'panel_user']);

        $admin = new User;
        $admin->name = 'Administrador';
        $admin->email = 'doc@admin.com';
        $admin->password = bcrypt('doc@admin.com');
        $admin->save();

        $admin->assignRole($adminRole);

        /* $pro = new User;
        $pro->name = 'Profesional';
        $pro->email = 'doc@pro.com';
        $pro->password = bcrypt('doc@pro.com');
        $pro->save();

        $pro->assignRole($proRole); */

        $standard = new User;
        $standard->name = 'General';
        $standard->email = 'doc@standard.com';
        $standard->password = bcrypt('doc@standard.com');
        $standard->save();

        $standard->assignRole($standardRole);

        $basic = new User;
        $basic->name = 'Usuario';
        $basic->email = 'doc@basic.com';
        $basic->password = bcrypt('doc@basic.com');
        $basic->save();

        $basic->assignRole($basicRole);

    }
}
