<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = new User();
        $admin->name = "Admin";
        $admin->email = "admin@test.com";
        $admin->password = Hash::make("123123123");
        $admin->email_verified_at = now();
        $admin->save();
    }
}
