<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin User',
                'email' => 'admin@skillkart.test',
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ],
            [
                'name' => 'Trainer User',
                'email' => 'trainer@skillkart.test',
                'password' => Hash::make('password123'),
                'role' => 'trainer',
            ],
            [
                'name' => 'Student User',
                'email' => 'student@skillkart.test',
                'password' => Hash::make('password123'),
                'role' => 'student',
            ],
            [
                'name' => 'Parent User',
                'email' => 'parent@skillkart.test',
                'password' => Hash::make('password123'),
                'role' => 'parent',
            ],
            [
                'name' => 'Supervisor User',
                'email' => 'supervisor@skillkart.test',
                'password' => Hash::make('password123'),
                'role' => 'supervisor',
            ],
            [
                'name' => 'Other User',
                'email' => 'other@skillkart.test',
                'password' => Hash::make('password123'),
                'role' => 'others',
            ],
        ];

        foreach ($users as $item) {
            $user = User::updateOrCreate(
                ['email' => $item['email']],
                [
                    'name' => $item['name'],
                    'password' => $item['password'],
                ]
            );

            $user->syncRoles([$item['role']]);
        }
    }
}