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
                'name' => 'demo',
                'email' => 'demo@gmail.com',
                'password' => bcrypt('demo1234'),
            ],
        ];
        foreach ($usuarios as $usuario) {
            User::create($usuario);
        }
    }
}
