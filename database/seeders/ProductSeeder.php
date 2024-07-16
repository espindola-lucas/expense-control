<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locale = config('app.faker_locale');
        $faker = Faker::create($locale);
        $products = [];

        $startYear = 2024;
        $endYear = 2030;

        for ($year = $startYear; $year <= $endYear; $year++) {
            for ($month = 1; $month <= 12; $month++) {
                $lastDayOfMonth = date('t', strtotime("$year-$month-01"));
                for ($day = 1; $day <= 12; $day++) {
                    $products[] = [
                        'name' => $faker->word,
                        'price' => $faker->numberBetween(2, 90) . '.000',
                        'user_id' => 1,
                        'expense_date' => $faker->dateTimeBetween("$year-$month-01", "$year-$month-$lastDayOfMonth")->format('Y-m-d')
                    ];
                }
            }
        }
        foreach (array_chunk($products, 1000) as $chunk) {
            Product::insert($chunk);
        }
    }
}
