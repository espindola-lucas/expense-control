<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Actions\RecurringPayment\GenerateDueOccurrencesAction;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SyncRecurringPayments
{
    public function __construct(private readonly GenerateDueOccurrencesAction $action)
    {
    }

    /**
     * Lazily materializes any due recurring payments for the authenticated user, at most
     * once per day (the cache key is just a performance guard — the real idempotency
     * mechanism is the partial unique index on movements(recurring_payment_id, movement_date)).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $cacheKey = "recurring_payments_synced:{$user->id}:".now()->toDateString();

            Cache::remember($cacheKey, now()->endOfDay(), function () use ($user) {
                $this->action->execute($user->id);

                return true;
            });
        }

        return $next($request);
    }
}
