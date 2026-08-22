<?php
declare(strict_types=1);

namespace App\Actions\RecurringPayment;

use App\Enums\RecurringFrequency;
use App\Models\Movement;
use App\Models\RecurringPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GenerateDueOccurrencesAction
{
    public function execute(int $userId): void
    {
        $rules = RecurringPayment::where('user_id', $userId)
            ->where('is_active', true)
            ->get();

        foreach ($rules as $rule) {
            $this->generateForRule($rule->id);
        }
    }

    private function generateForRule(int $recurringPaymentId): void
    {
        DB::transaction(function () use ($recurringPaymentId) {
            // Lock the rule row so two concurrent requests for the same user can't both
            // materialize the same occurrence before either commits.
            $rule = RecurringPayment::where('id', $recurringPaymentId)->lockForUpdate()->first();

            if (! $rule || ! $rule->is_active) {
                return;
            }

            $cursor = $rule->last_generated_date
                ? $this->nextOccurrence($rule->frequency, Carbon::parse($rule->last_generated_date))
                : Carbon::parse($rule->start_date);

            $today = Carbon::today();
            $endDate = $rule->end_date ? Carbon::parse($rule->end_date) : null;

            while ($cursor->lte($today) && (! $endDate || $cursor->lte($endDate))) {
                // firstOrCreate + the partial unique index on (recurring_payment_id, movement_date)
                // is what actually guarantees idempotency, not this in-memory loop.
                Movement::firstOrCreate(
                    [
                        'recurring_payment_id' => $rule->id,
                        'movement_date' => $cursor->toDateString(),
                    ],
                    [
                        'user_id' => $rule->user_id,
                        'account_id' => $rule->account_id,
                        'category_id' => $rule->category_id,
                        'type' => $rule->type,
                        'name' => $rule->name,
                        'amount' => $rule->amount,
                    ]
                );

                $rule->last_generated_date = $cursor->toDateString();
                $cursor = $this->nextOccurrence($rule->frequency, $cursor);
            }

            $rule->save();
        });
    }

    private function nextOccurrence(RecurringFrequency $frequency, Carbon $from): Carbon
    {
        return match ($frequency) {
            RecurringFrequency::Weekly => $from->copy()->addWeek(),
            RecurringFrequency::Monthly => $from->copy()->addMonthNoOverflow(),
            RecurringFrequency::Yearly => $from->copy()->addYearNoOverflow(),
        };
    }
}
