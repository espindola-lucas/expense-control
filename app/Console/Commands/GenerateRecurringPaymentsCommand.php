<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\RecurringPayment\GenerateDueOccurrencesAction;
use App\Models\User;
use Illuminate\Console\Command;

class GenerateRecurringPaymentsCommand extends Command
{
    protected $signature = 'recurring-payments:generate {--user= : Limit generation to a single user ID}';

    protected $description = 'Materializes due occurrences of active recurring payments into movements. '
        .'Today this runs lazily via App\Http\Middleware\SyncRecurringPayments on authenticated API '
        .'requests (no cron/queue worker exists in docker-compose yet). When a real scheduler is added, '
        ."wire this up in routes/console.php: Schedule::command('recurring-payments:generate')->daily();";

    public function __construct(private readonly GenerateDueOccurrencesAction $action)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $userOption = $this->option('user');

        $userIds = $userOption
            ? [(int) $userOption]
            : User::query()->pluck('id')->all();

        foreach ($userIds as $userId) {
            $this->action->execute($userId);
        }

        $this->info('Recurring payments synced for '.count($userIds).' user(s).');

        return self::SUCCESS;
    }
}
