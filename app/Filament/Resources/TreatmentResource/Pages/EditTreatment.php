<?php

namespace App\Filament\Resources\TreatmentResource\Pages;

use App\Filament\Resources\TreatmentResource;
use App\Models\PastMedicalhistory;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTreatment extends EditRecord
{
    protected static string $resource = TreatmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }




    protected function afterSave(): void
    {
        $treatmentId = $this->getRecord()->id;
        $rec = PastMedicalhistory::where('treatments_id', $treatmentId)->first();
        if (!is_null($rec->historyDate) || !is_null($rec->description)){
            $rec->individual_id = $this->getRecord()->individual_id;

        }else{
            $rec->individual_id = null;

        }
        $rec->save();
        //dd($rec);
    }




}
