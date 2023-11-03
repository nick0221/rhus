<?php

namespace App\Filament\Resources\TreatmentResource\Pages;

use App\Filament\Resources\TreatmentResource;
use App\Models\PastMedicalhistory;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTreatment extends CreateRecord
{
    protected static string $resource = TreatmentResource::class;


    protected ?string $heading = 'Create New Individual Treatment';



    protected function afterCreate(): void
    {
        $treatmentId = $this->getRecord()->id;
        $rec = PastMedicalhistory::where('treatments_id', $treatmentId)->first();
        if (!is_null($rec->historyDate) || !is_null($rec->description)){
            $rec->individual_id = $this->getRecord()->individual_id;
            $rec->save();
        }
        //dd($rec);

    }






}
