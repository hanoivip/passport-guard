<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');
        //Permission seeding
        Permission::create(['name' => 'statistics']);
        Permission::create(['name' => 'manage_user']);
        Permission::create(['name' => 'manage_server']);
        Permission::create(['name' => 'manage_content']);
        Permission::create(['name' => 'manage_gift']);
        Permission::create(['name' => 'manage_site']);
        //Role seeding
        $role = Role::create(['name' => 'user']);
        $role = Role::create(['name' => 'marketer']);
        $role->syncPermissions(['statistics', 'manage_content', 'manage_gift']);
        $role = Role::create(['name' => 'owner']);
        $role->syncPermissions(Permission::all());
    }
}
