<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Spent;
use Carbon\Carbon;

class SpentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Primer registro
        Spent::create([
            'expense_date' => Carbon::now(),
            'name' => 'Gasto 1',
            'price' => 100,
            'user_id' => 1, // Refiriéndose al ID de un usuario previamente creado
        ]);

        // Segundo registro
        Spent::create([
            'expense_date' => Carbon::now(),
            'name' => 'Gasto 2',
            'price' => 250,
            'user_id' => 1, // Refiriéndose al ID de un usuario previamente creado
        ]);

        // Tercer registro
        Spent::create([
            'expense_date' => Carbon::now(),
            'name' => 'Gasto 3',
            'price' => 150,
            'user_id' => 1, // Refiriéndose al ID de un usuario previamente creado
        ]);
    }
}
