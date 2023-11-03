<?php

namespace App\Filament\Resources\IndividualResource\Pages;

use App\Filament\Resources\IndividualResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateIndividual extends CreateRecord
{
    protected static string $resource = IndividualResource::class;


    protected ?string $heading = 'Create Profile';



    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }


    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Confirmation')
            ->body('New record has been successfully created.');
    }


}
