<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PersonalConfiguration;
use Carbon\Carbon;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PersonalConfiguration::create([
            'start_counting' => Carbon::now(),
            'end_counting' => Carbon::now()->addDay(),
            'available_money' => 5000,
            'month_available_money' => Carbon::now()->format('F'), 
            'expense_percentage_limit' => 80,
            'user_id' => 1,
        ]);
    }
}
