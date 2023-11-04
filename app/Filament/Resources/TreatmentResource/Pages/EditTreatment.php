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
        $recPastMedical = PastMedicalhistory::where('treatments_id', $treatmentId)->first();
        if (!is_null($recPastMedical->historyDate) || !is_null($recPastMedical->description)){
            $recPastMedical->individual_id = $this->getRecord()->individual_id;
        }else{
            $recPastMedical->individual_id = null;

        }


        $recFamilyHistory = PastMedicalhistory::where('treatments_id', $treatmentId)->first();
        if (!is_null($recFamilyHistory->historyDate) || !is_null($recFamilyHistory->description)){
            $recFamilyHistory->individual_id = $this->getRecord()->individual_id;
        }else{
            $recFamilyHistory->individual_id = null;

        }


        $recTravelHistory = PastMedicalhistory::where('treatments_id', $treatmentId)->first();
        if (!is_null($recTravelHistory->dateoftravel) || !is_null($recTravelHistory->place) || !is_null($recTravelHistory->daysofstay)){
            $recTravelHistory->individual_id = $this->getRecord()->individual_id;
        }else{
            $recTravelHistory->individual_id = null;

        }







        $recFamilyHistory->save();
        $recPastMedical->save();
        $recTravelHistory->save();
        //dd($rec);





    }




}
