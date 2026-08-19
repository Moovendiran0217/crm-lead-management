<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@example.com',
            'phone' => '+919999999999',
            'role' => UserRole::ADMIN,
            'status' => UserStatus::ACTIVE,
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Sales Employee One',
            'email' => 'sales1@example.com',
            'phone' => '+919999999998',
            'role' => UserRole::SALES,
            'status' => UserStatus::ACTIVE,
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Sales Employee Two',
            'email' => 'sales2@example.com',
            'phone' => '+919999999997',
            'role' => UserRole::SALES,
            'status' => UserStatus::ACTIVE,
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Inactive Sales',
            'email' => 'inactive-sales@example.com',
            'phone' => '+919999999996',
            'role' => UserRole::SALES,
            'status' => UserStatus::INACTIVE,
            'password' => Hash::make('password'),
        ]);
    }
}
