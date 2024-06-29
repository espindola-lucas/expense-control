<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
        [
            'id' => '1',
            'name' => 'peluqueria',
            'price' => '6.000',
            'user_id' => '1',
            'expense_date' => '06/01/2024'
        ],
        [
            'id' => '2',
            'name' => 'comida pichis',
            'price' => '26.000',
            'user_id' => '1',
            'expense_date' => '06/03/2024'
        ],
        [
            'id' => '3',
            'name' => 'pan',
            'price' => '1.000',
            'user_id' => '1',
            'expense_date' => '06/07/2024'
        ],
        [
            'id' => '4',
            'name' => 'nafata',
            'price' => '45.000',
            'user_id' => '1',
            'expense_date' => '06/10/2024'
        ]
        ]);
    }
}
