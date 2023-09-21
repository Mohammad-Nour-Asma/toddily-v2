<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AgeSection;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

         \App\Models\User::create([
             'first_name' => 'Admin',
             'last_name' => 'Admin',
             'username' => 'admin',
             'role_id'=> 1,
             'phone' => '0954942265',
             'password'=> bcrypt('admin'),
         ]);

         Role::create([
            'role_name'=>'admin',
         ]);
        Role::create([
            'role_name'=>'teacher',
        ]);
        Role::create([
            'role_name'=>'doctor',
        ]);
        Role::create([
            'role_name'=>'social',
        ]);
        Role::create([
            'role_name'=>'parent',
        ]);  Role::create([
            'role_name'=>'extra',
        ]);

    }
}
