<?php

namespace App\Filament\Resources\IndividualResource\Widgets;

use App\Models\FollowupCheckup;
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
        $followupCheckupToday = FollowupCheckup::whereDate('followupDate', now()->toDateString())
            ->where('followupStatus', 3)
            ->count();

        $dateToday = date('d/m/Y');
        $filterTodayStr = urldecode("tableFilters%5Bcreated_at%5D%5Bcreated_at%5D");
        $param = urlencode("{$dateToday} - {$dateToday}");


        $dateTodayFollowup = date('M d,Y');
        $followupfilterTodayStr = urldecode("tableFilters%5Bfollowup%5D%5BcheckupDate%5D");
        $param2 = urlencode("{$dateTodayFollowup}");


        return [
            Stat::make('Total Patient Recorded', $ttlInd)
                ->url(route('filament.admin.resources.individuals.index'))
                ->extraAttributes(['class' => '']),

            Stat::make('Total Treatments Recorded', $ttlTreatment)
                ->url(route('filament.admin.resources.treatments.index'))
                ->extraAttributes(['class' => '']),

            Stat::make('Total Treatments Today', $ttlTreatmentToday)
                ->url(route('filament.admin.resources.treatments.index', [($filterTodayStr) => urldecode($param)]))
                ->extraAttributes(['class' => '']),

            Stat::make('No. of To Followup Checkup Today', $followupCheckupToday)
                ->url(route('filament.admin.resources.treatments.index', [($followupfilterTodayStr) => urldecode($param2)]))

                ->extraAttributes(['class' => '']),

        ];
    }
}
