<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Configuration;

class DefaultConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Configuracion::create([
            'id' => '1',
            'start_counting' => '06/01/2024',
            'filter' => 'Ambos',
            'available_money' => '300.000',
            'user_id' => '1'
        ]);
    }
}
