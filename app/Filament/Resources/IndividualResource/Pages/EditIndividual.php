<?php

namespace App\Filament\Resources\IndividualResource\Pages;

use App\Filament\Resources\IndividualResource;
use App\Models\Individual;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditIndividual extends EditRecord
{
    protected static string $resource = IndividualResource::class;

    protected function afterSave(): void
    {
        $ind = Individual::where('id', $this->getRecord()->id)->first();
        $ind->fullname = Str::ucfirst($ind->firstname .' '.$ind->middlename.' '.$ind->lastname.' '.$ind->extname);
        $ind->save();

    }



    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }


    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Confirmation')
            ->duration(5000)
            ->body('Profile '.$this->getRecord()->fullname.' has been successfully updated.');
    }


    protected function getHeaderActions(): array
    {
        return [
            //Actions\DeleteAction::make(),
        ];
    }
}
