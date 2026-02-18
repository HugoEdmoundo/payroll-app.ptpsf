<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->updateOrInsert(
            ['email' => 'superadmin@hugedm.fun'],
            [
                'name' => 'Superadmin',
<<<<<<< HEAD
                'email' => 'superadmin@hugedm.fun',
=======
>>>>>>> 0cf1b5275661aee298a4278056bc54bbc662cffa
                'password' => Hash::make('password123'),
                'role_id' => 1, // Super Admin
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}