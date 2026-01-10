<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        AdminUser::create([
            'username' => 'admin',
            'password' => Hash::make('changeme'),
            'email' => 'admin@blogbb.local',
            'role' => 'owner',
        ]);
    }
}
