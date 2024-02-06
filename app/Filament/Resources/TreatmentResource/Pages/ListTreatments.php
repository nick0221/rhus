<?php

namespace App\Filament\Resources\TreatmentResource\Pages;

use App\Filament\Resources\TreatmentResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListTreatments extends ListRecords
{
    protected static string $resource = TreatmentResource::class;

    protected function getHeaderActions(): array
    {


        return [
            Actions\CreateAction::make()->label('Create new')
                ->icon('heroicon-o-plus'),

            ExportAction::make()->color('info')
                ->exports([
                    ExcelExport::make()->fromTable()
                        ->withColumns([
                            Column::make('individual.isMember')->heading('Is Member')
                                ->formatStateUsing(fn ($state) => ($state === 1) ? 'Yes':'No'),

                            Column::make('isDependent')->heading('Is Dependent')
                                ->formatStateUsing(fn ($state) => ($state === 1) ? 'Yes':'No'),

                            Column::make('individual.philhealthnum')->heading('Phil Health Number'),

                            Column::make('vitalSign')->heading('Vitals(Hr, Pr, Rr, Temp, Bp)'),
                            Column::make('diagnosis')->heading('Diagnosis'),
                            Column::make('medication')->heading('Medication'),
                            Column::make('chiefComplaints')->heading('Chief Complaints'),
                            Column::make('attendingProvider')->heading('Attending Provider'),
                            Column::make('dependentPhilHealthNum')->heading('Dependent\'s Phil Health Number (if Dependent)'),
                            Column::make('birthday')->heading('Dependent\'s Birthday'),
                            Column::make('phMemberName')->heading('Dependent\'s Member Name'),

                        ])
                        ->except(['individuals.created_at', 'individuals.id', 'treatments.id', 'individual_id', 'treatments.category_id', 'id', ])
                        ->withFilename(fn () => 'ExportedTreatmentRecords-'.now()->toDateString())

                   ])

        ];
    }


//
//    public function getTabs(): array
//    {
//
//        return [
//            'all' => Tab::make('All'),
//
//            'toFollow' => Tab::make('To Follow')
//                ->modifyQueryUsing(fn (Builder $query) => $query->where('followupStatus', 3)
//                    ->select('treatments.*', 'followupStatus')
//                    ->leftjoin('followup_checkups', 'followup_checkups.treatment_id', '=', 'treatments.id')
//                ),
//
//            'done' => Tab::make('Done')
//                ->modifyQueryUsing(fn (Builder $query) => $query->where('followupStatus', 1)
//                    ->select('treatments.*', 'followupStatus')
//                    ->leftjoin('followup_checkups', 'followup_checkups.treatment_id', '=', 'treatments.id')
//                ),
//
//            'notShow' => Tab::make('Not Show')
//                ->modifyQueryUsing(fn (Builder $query) => $query->where('followupStatus', 2)
//                    ->select('treatments.*', 'followupStatus')
//                    ->leftjoin('followup_checkups', 'followup_checkups.treatment_id', '=', 'treatments.id')
//                ),
//        ];
//
//    }


}
