<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create role admin
        // Role::create(['name' => 'admin']);
        //
        $user = \App\Models\User::factory()->create([
            'email' => 'userdemo@gmail.com',
            'password' => bcrypt('userdemo'),
            'name' => 'Admin',
        ]);
        // assign role admin
        $user->assignRole('admin');
        
        $role = Role::where('name', 'admin')->first();
        if ($role) {
            $permissions = [
                Permission::create(['name' => 'users.*']),
                Permission::create(['name' => 'roles.*']),
                Permission::create(['name' => 'navigations.*']),
                Permission::create(['name' => 'preferences.*']),
            ];

            // Berikan multiple permissions ke peran
            $role->syncPermissions($permissions);
        }

        // create role employee
        Role::create(['name' => 'employee']);
        //
        $operator = \App\Models\User::factory()->create([
            'email' => 'operatordemo@gmail.com',
            'password' => bcrypt('operatordemo'),
            'name' => 'Operator',
        ]);
        // assign role employee
        $operator->assignRole('employee');
    }
}
