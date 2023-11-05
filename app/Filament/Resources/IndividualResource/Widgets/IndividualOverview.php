<?php

namespace App\Filament\Resources\IndividualResource\Widgets;

use App\Models\Individual;
use App\Models\Treatment;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class IndividualOverview extends BaseWidget
{

    public static function canView(): bool
    {
        return true;
    }

    /**
     * @return array|int|string
     */



    protected function getStats(): array
    {
        $ttlInd = Individual::count();
        $ttlTreatment = Treatment::count();
        $ttlTreatmentToday = Treatment::whereDate('created_at', now()->toDate())->count();

        return [
            Stat::make('Total Individuals Recorded', $ttlInd)
                ->extraAttributes(['class' => '']),

            Stat::make('Total Treatments Recorded', $ttlTreatment)
                ->extraAttributes(['class' => '']),

            Stat::make('Total Treatments Today', $ttlTreatmentToday)
                ->extraAttributes(['class' => '']),

        ];
    }
}
