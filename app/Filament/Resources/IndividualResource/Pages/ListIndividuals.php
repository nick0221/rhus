<?php

namespace App\Filament\Resources\IndividualResource\Pages;

use App\Filament\Resources\IndividualResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListIndividuals extends ListRecords
{
    protected static string $resource = IndividualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Create New')
                ->icon('heroicon-o-plus'),

            ExportAction::make()->color('info')
                ->exports([
                    ExcelExport::make()->fromTable()
                        ->withColumns([
                            Column::make('isMember')->heading('Is Member')
                                ->formatStateUsing(fn ($state) => ($state === 1) ? 'Yes':'No'),

                            Column::make('mobile'),
                            Column::make('address'),
                            Column::make('gender'),
                            Column::make('birthdate'),
                            Column::make('height'),
                            Column::make('weight'),
                            Column::make('placeofbirth'),
                            Column::make('occupation'),
                            Column::make('guardianName')->heading('Guardian Name'),
                            Column::make('guardianContact')->heading('Guardian Contact'),



                        ])
                        ->except(['image'])
                        ->withFilename(fn () => 'ExportedPatientRecords-'.now()->toDateString())

                ])
        ];
    }
}
