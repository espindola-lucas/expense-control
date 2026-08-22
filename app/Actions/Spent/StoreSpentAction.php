<?php
declare(strict_types=1);

namespace App\Actions\Spent;

use App\Actions\Account\GetOrCreateDefaultAccountAction;
use App\Actions\Category\GetOrCreateDefaultCategoryAction;
use App\Enums\CategoryType;
use App\Enums\MovementType;
use App\Models\Movement;

class StoreSpentAction
{
    public function __construct(
        private GetOrCreateDefaultAccountAction $getOrCreateDefaultAccountAction,
        private GetOrCreateDefaultCategoryAction $getOrCreateDefaultCategoryAction,
    ) {
    }

    public function execute(array $data, int $userId): Movement
    {
        $account = $this->getOrCreateDefaultAccountAction->execute($userId);
        $category = $this->getOrCreateDefaultCategoryAction->execute($userId, CategoryType::Expense);

        return Movement::create([
            'user_id'       => $userId,
            'account_id'    => $account->id,
            'category_id'   => $category->id,
            'type'          => MovementType::Expense,
            'name'          => trim($data['spentName']),
            'amount'        => $data['price'],
            'movement_date' => $data['expense_date'],
        ]);
    }
}
