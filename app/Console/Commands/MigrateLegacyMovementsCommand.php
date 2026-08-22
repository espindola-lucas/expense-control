<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Account\GetOrCreateDefaultAccountAction;
use App\Actions\Category\GetOrCreateDefaultCategoryAction;
use App\Enums\CategoryType;
use App\Enums\MovementType;
use App\Models\Movement;
use App\Models\Sell;
use App\Models\Spent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateLegacyMovementsCommand extends Command
{
    protected $signature = 'movements:migrate-legacy {--dry-run : Solo reporta conteos, no inserta nada}';

    protected $description = 'Migra los registros legacy de spents y sells a la tabla unificada movements';

    public function __construct(
        private readonly GetOrCreateDefaultAccountAction $getOrCreateDefaultAccountAction,
        private readonly GetOrCreateDefaultCategoryAction $getOrCreateDefaultCategoryAction,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $userIds = Spent::query()->distinct()->pluck('user_id')
            ->merge(Sell::query()->distinct()->pluck('user_id'))
            ->unique()
            ->values();

        if ($userIds->isEmpty()) {
            $this->info('No hay spents ni sells para migrar.');

            return self::SUCCESS;
        }

        foreach ($userIds as $userId) {
            $this->migrateUser((int) $userId, $dryRun);
        }

        return self::SUCCESS;
    }

    private function migrateUser(int $userId, bool $dryRun): void
    {
        if (Movement::where('user_id', $userId)->exists()) {
            $this->warn("Usuario {$userId}: ya tiene movements, se omite (posible ejecución previa).");

            return;
        }

        $spentsCount = Spent::where('user_id', $userId)->count();
        $sellsCount = Sell::where('user_id', $userId)->count();

        if ($dryRun) {
            $this->line("Usuario {$userId}: {$spentsCount} spents, {$sellsCount} sells por migrar (dry-run).");

            return;
        }

        DB::transaction(function () use ($userId) {
            $account = $this->getOrCreateDefaultAccountAction->execute($userId);
            $expenseCategory = $this->getOrCreateDefaultCategoryAction->execute($userId, CategoryType::Expense);
            $incomeCategory = $this->getOrCreateDefaultCategoryAction->execute($userId, CategoryType::Income);

            Spent::where('user_id', $userId)
                ->chunkById(200, function ($spents) use ($account, $expenseCategory) {
                    foreach ($spents as $spent) {
                        Movement::forceCreate([
                            'user_id' => $spent->user_id,
                            'account_id' => $account->id,
                            'category_id' => $expenseCategory->id,
                            'type' => MovementType::Expense,
                            'name' => trim($spent->name),
                            'amount' => $spent->price,
                            'movement_date' => $spent->expense_date,
                            'created_at' => $spent->created_at,
                            'updated_at' => $spent->updated_at,
                        ]);
                    }
                });

            Sell::where('user_id', $userId)
                ->chunkById(200, function ($sells) use ($account, $incomeCategory) {
                    foreach ($sells as $sell) {
                        Movement::forceCreate([
                            'user_id' => $sell->user_id,
                            'account_id' => $account->id,
                            'category_id' => $incomeCategory->id,
                            'type' => MovementType::Income,
                            'name' => trim($sell->name),
                            'amount' => $sell->price,
                            'movement_date' => $sell->sell_date,
                            'created_at' => $sell->created_at,
                            'updated_at' => $sell->updated_at,
                        ]);
                    }
                });
        });

        $this->info("Usuario {$userId}: migrados {$spentsCount} spents y {$sellsCount} sells.");
    }
}
