<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Permission list
        Permission::create(['name' => 'products.index']);
        Permission::create(['name' => 'products.edit']);
        Permission::create(['name' => 'products.show']);
        Permission::create(['name' => 'products.create']);
        Permission::create(['name' => 'products.destroy']);

        //Admin
        $admin = Role::create(['name' => 'Admin']);

        $admin->givePermissionTo([
            'products.index',
            'products.edit',
            'products.show',
            'products.create',
            'products.destroy'
        ]);
        //$admin->givePermissionTo('products.index');
        //$admin->givePermissionTo(Permission::all());

        //Guest
        $guest = Role::create(['name' => 'Guest']);

        $guest->givePermissionTo([
            'products.index',
            'products.show'
        ]);

        //User Admin
        $user = User::find(1); //Usuario Demo
        $user->assignRole('Admin');
    }
}
