<?php

namespace App\Filament\Resources\IndividualResource\Pages;

use App\Filament\Resources\IndividualResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewIndividual extends ViewRecord
{
    protected static string $resource = IndividualResource::class;


    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('info')
                ->icon('heroicon-o-pencil-square')
                ->label('Update personal Info'),
        ];
    }


}
