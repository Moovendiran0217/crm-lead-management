<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),

            'email' => fake()->unique()->safeEmail(),

            'phone' => '+91' . fake()->numerify('##########'),

            'role' => UserRole::SALES,

            'status' => UserStatus::ACTIVE,

            'password' => Hash::make('password'),

            'remember_token' => Str::random(10),
        ];
    }
}
