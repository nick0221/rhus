<?php

namespace App\Filament\Resources\IndividualResource\Pages;

use App\Filament\Resources\IndividualResource;
use App\Models\Individual;
use Filament\Actions;
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



    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
