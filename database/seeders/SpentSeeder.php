<?php

namespace Database\Seeders;

use App\Actions\Account\GetOrCreateDefaultAccountAction;
use App\Actions\Category\GetOrCreateDefaultCategoryAction;
use App\Enums\CategoryType;
use App\Enums\MovementType;
use App\Models\Movement;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userId = 1; // Refiriéndose al ID de un usuario previamente creado

        $account = app(GetOrCreateDefaultAccountAction::class)->execute($userId);
        $category = app(GetOrCreateDefaultCategoryAction::class)->execute($userId, CategoryType::Expense);

        $names = ['Gasto 1', 'Gasto 2', 'Gasto 3'];
        $prices = [100, 250, 150];

        foreach ($names as $index => $name) {
            Movement::create([
                'user_id' => $userId,
                'account_id' => $account->id,
                'category_id' => $category->id,
                'type' => MovementType::Expense,
                'name' => $name,
                'amount' => $prices[$index],
                'movement_date' => Carbon::now(),
            ]);
        }
    }
}
