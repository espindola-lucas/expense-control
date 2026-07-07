<?php

declare(strict_types=1);

namespace App\Actions\Spent;

use App\Helpers\Helps;
use App\Models\PersonalConfiguration;
use App\Models\Spent;
use Carbon\Carbon;

class GetDashboardDataAction
{
    public function execute(
        int $userId,
        string $type,
        ?string $selectedMonth,
        ?string $filterText,
        ?string $startDateParam,
        ?string $endDateParam,
    ): array {
        $config = Helps::getAllConfiguration($userId);
        $getAllPeriods = Helps::getAllPeriods($userId, 'personal');
        $lastConfiguration = $this->getConfigurationForMonth($userId);
        $hasConfiguration = ! $config->isEmpty();

        if ($selectedMonth) {
            $selectedPeriod = $getAllPeriods->firstWhere('month_available_money', $selectedMonth);
            $startDate = $selectedPeriod?->start_counting ?? Helps::getStartDateFromDatabase($userId, 'personal');
            $endDate = $selectedPeriod?->end_counting ?? Helps::getEndDateFromDatabase($userId, 'personal');
        } else {
            $startDate = $startDateParam ?? Helps::getStartDateFromDatabase($userId, 'personal');
            $endDate = $endDateParam ?? Helps::getEndDateFromDatabase($userId, 'personal');
        }

        if ($filterText) {
            return [
                'isFilter' => true,
                'spents' => Helps::filterByText($userId, $filterText, $type),
                'allPeriods' => $getAllPeriods,
                'lastConfiguration' => $lastConfiguration,
                'hasConfiguration' => $hasConfiguration,
                'type' => $type,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ];
        }

        $data = Helps::filterByPeriod($userId, $startDate, $endDate, $type);
        $countSpents = $this->getTotalSpentsByPeriod($userId, $startDate, $endDate);
        $restMoney = $this->getRestMoney($data['availableMoney'], $data['totalPrice']);
        $formatted = $this->formatValues($data, $restMoney);
        $percentageUsed = $this->getPercentageUsed($data['totalPrice'], $data['availableMoney']);

        return [
            'isFilter' => false,
            'spents' => $data['spents'],
            'allPeriods' => $getAllPeriods,
            'monthly_balance' => $this->buildMonthlyBalance($formatted, $countSpents),
            'lastConfiguration' => $lastConfiguration,
            'hasConfiguration' => $hasConfiguration,
            'percentageUsed' => $this->buildPercentageMessage(
                $formatted['formattedAvailableMoney'],
                $percentageUsed,
                $lastConfiguration?->expense_percentage_limit ?? 0,
            ),
            'type' => $type,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

    private function getTotalSpentsByPeriod(int $userId, ?string $startDate, ?string $endDate): int
    {
        if ($startDate && $endDate) {
            return Spent::join('personal_configurations as c', function ($join) {
                $join->on('spents.expense_date', '>=', 'c.start_counting')
                    ->on('spents.expense_date', '<=', 'c.end_counting')
                    ->on('spents.user_id', '=', 'c.user_id');
            })
                ->where('c.start_counting', $startDate)
                ->where('c.end_counting', $endDate)
                ->where('spents.user_id', $userId)
                ->count();
        }

        return 0;
    }

    private function getRestMoney(int $availableMoney, int $totalPrice): int
    {
        return $availableMoney !== 0 ? $availableMoney - $totalPrice : 0;
    }

    private function formatValues(array $data, int $restMoney): array
    {
        return [
            'formattedAvailableMoney' => Helps::formatValue($data['availableMoney']),
            'formattedRestMoney' => Helps::formatValue($restMoney),
            'formattedTotalPrice' => Helps::formatValue($data['totalPrice']),
        ];
    }

    private function buildPercentageMessage(string $availableMoney, int|float $percentageUsed, int $limit): array
    {
        if (! empty($availableMoney)) {
            return [
                'message' => $percentageUsed >= $limit,
                'percentageUser' => $percentageUsed,
                'color' => $percentageUsed >= $limit ? 'red' : 'green',
            ];
        }

        return ['message' => false, 'percentageUser' => 0, 'color' => 'green'];
    }

    private function buildMonthlyBalance(array $formatted, int $countSpents): array
    {
        return [
            'avalaibleMoney' => $formatted['formattedAvailableMoney'],
            'totalPrice' => $formatted['formattedTotalPrice'],
            'restMoney' => $formatted['formattedRestMoney'],
            'countSpent' => $countSpents,
        ];
    }

    private function getPercentageUsed(int $totalPrice, int $availableMoney): int|float
    {
        if ($totalPrice && $availableMoney) {
            return round(($totalPrice / $availableMoney) * 100, 1, PHP_ROUND_HALF_UP);
        }

        return 0;
    }

    private function getConfigurationForMonth(int $userId): ?PersonalConfiguration
    {
        $configuration = PersonalConfiguration::where('user_id', $userId)
            ->orderBy('end_counting', 'desc')
            ->first();

        if ($configuration instanceof PersonalConfiguration) {
            $this->formatConfigurationDates($configuration);
        }

        return $configuration;
    }

    private function formatConfigurationDates(PersonalConfiguration $configuration): void
    {
        if (! is_null($configuration->start_counting) && ! is_null($configuration->end_counting)) {
            $configuration->start_counting = $this->formatDate($configuration->start_counting);
            $configuration->end_counting = $this->formatDate($configuration->end_counting);
        } elseif (! is_null($configuration->start_counting)) {
            $configuration->start_counting = $this->formatDate($configuration->start_counting);
        } else {
            $configuration->end_counting = $this->formatDate($configuration->end_counting);
        }
    }

    private function formatDate(string $date): string
    {
        return Carbon::createFromFormat('Y-m-d', str_replace('/', '-', $date))->format('d/m/y');
    }
}
