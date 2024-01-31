<?php

namespace App\Filament\Resources\TreatmentResource\Pages;

use App\Filament\Resources\TreatmentResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListTreatments extends ListRecords
{
    protected static string $resource = TreatmentResource::class;

    protected function getHeaderActions(): array
    {


        return [
            Actions\CreateAction::make()->label('Create new')
                ->icon('heroicon-o-plus'),
        ];
    }



    public function getTabs(): array
    {

        return [
            'all' => Tab::make('All'),

            'toFollow' => Tab::make('To Follow')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('followupStatus', 0)
                    ->select('treatments.*', 'followupStatus')
                    ->leftjoin('followup_checkups', 'followup_checkups.treatment_id', '=', 'treatments.id')),

            'done' => Tab::make('Done')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('followupStatus', 1)
                    ->select('treatments.*', 'followupStatus')
                    ->leftjoin('followup_checkups', 'followup_checkups.treatment_id', '=', 'treatments.id')
                ),

            'notShow' => Tab::make('Not Show')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('followupStatus', 2)
                    ->select('treatments.*', 'followupStatus')
                    ->leftjoin('followup_checkups', 'followup_checkups.treatment_id', '=', 'treatments.id')
                ),
        ];

    }


}
