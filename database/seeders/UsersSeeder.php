<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = [
            [
                'name' => 'Juan Perez',
                'email' => 'juan@example.com',
                'password' => bcrypt('password123'),
            ],
        ];
        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }
    }
}
