<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();

        \App\Models\Role::create(['name' => 'administrator', 'display_name' => 'Administrador']);

        $lawyer_role = \App\Models\Role::create(['name' => 'lawyer', 'display_name' => 'Abogado']);

        DB::table('users')->update(['role_id' => $lawyer_role->id]);

        $permission  = \App\Models\Permission::create(['name' => 'Inicio', 'path_name' => '/inicio', 'icon' => 'fa fa-home', 'icon_class' => '', 'parent_id' => null]);

        \App\Models\PermissionRole::create(['permission_id' => $permission->id, 'role_id' => $lawyer_role->id]);

    }
}
