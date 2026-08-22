<?php

declare(strict_types=1);

namespace App\Enums;

enum MovementType: string
{
    case Expense = 'expense';
    case Income = 'income';
    case Transfer = 'transfer';
}
