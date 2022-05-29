<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $users = [
            ['firstname' => 'Admin', 'lastname' => 'User', 'email' => 'admin@admin.com', 'password' => Hash::make('H6rkh87u'), 'is_admin' => true],
            ['firstname' => 'Marcel', 'lastname' => 'Breuer', 'email' => 'breuer.marcel@outlook.com', 'password' => Hash::make('H6rkh87u'), 'is_admin' => false]
        ];

        DB::table('users')->insert($users);
    }
}
