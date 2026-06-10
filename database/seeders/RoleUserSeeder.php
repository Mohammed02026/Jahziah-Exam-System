<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('123456');

        User::updateOrCreate(
            ['email' => 'm@example.com'],
            [
                'name' => 'Admin',
                'role' => UserRole::Admin->value,
                'password' => $password,
            ]
        );

        User::updateOrCreate(
            ['email' => 'a@example.com'],
            [
                'name' => 'Doctor A',
                'role' => UserRole::Instructor->value,
                'password' => $password,
            ]
        );

        User::updateOrCreate(
            ['email' => 't@example.com'],
            [
                'name' => 'Doctor T',
                'role' => UserRole::Instructor->value,
                'password' => $password,
            ]
        );

        User::updateOrCreate(
            ['email' => 'mohammed@example.com'],
            [
                'name' => 'Mohammed',
                'role' => UserRole::Student->value,
                'password' => $password,
            ]
        );

        User::updateOrCreate(
            ['email' => 'talal@example.com'],
            [
                'name' => 'Talal',
                'role' => UserRole::Student->value,
                'password' => $password,
            ]
        );

        User::updateOrCreate(
            ['email' => 'abdulsalam@example.com'],
            [
                'name' => 'Abdulsalam',
                'role' => UserRole::Student->value,
                'password' => $password,
            ]
        );
    }
}