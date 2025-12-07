<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios de prueba
        $users = [
            [
                'name' => 'Juan Pérez',
                'email' => 'juan@ejemplo.com',
                'password' => Hash::make('password123'),
                'icon' => 'public/icons/default.png',
            ],
            [
                'name' => 'María García',
                'email' => 'maria@ejemplo.com',
                'password' => Hash::make('password123'),
                'icon' => 'public/icons/default.png',
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos@ejemplo.com',
                'password' => Hash::make('password123'),
                'icon' => 'public/icons/default.png',
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana@ejemplo.com',
                'password' => Hash::make('password123'),
                'icon' => 'public/icons/default.png',
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
