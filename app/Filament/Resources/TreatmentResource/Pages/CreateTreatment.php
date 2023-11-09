<?php

namespace App\Filament\Resources\TreatmentResource\Pages;

use App\Filament\Resources\TreatmentResource;
use App\Models\FamilyHistory;
use App\Models\PastMedicalhistory;
use App\Models\TravelHistory;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTreatment extends CreateRecord
{

    protected static string $resource = TreatmentResource::class;

    protected ?string $heading = 'Create New Individual Treatment';



    protected function afterCreate(): void
    {
        $treatmentId = $this->getRecord()->id;

        $recPastMedical = PastMedicalhistory::where('treatments_id', $treatmentId)->first();
        if (!is_null($recPastMedical->historyDate) || !is_null($recPastMedical->description)){
            $recPastMedical->individual_id = $this->getRecord()->individual_id;
            $recPastMedical->save();
        }


        $recFamilyHistory = FamilyHistory::where('treatments_id', $treatmentId)->first();
        if (!is_null($recFamilyHistory->historyDate) || !is_null($recFamilyHistory->description)){
            $recFamilyHistory->individual_id = $this->getRecord()->individual_id;
            $recFamilyHistory->save();
        }


        $recTravelHistory = TravelHistory::where('treatments_id', $treatmentId)->first();
        if (!is_null($recTravelHistory->dateoftravel) || !is_null($recTravelHistory->place) || !is_null($recTravelHistory->daysofstay)){
            $recTravelHistory->individual_id = $this->getRecord()->individual_id;
            $recTravelHistory->save();
        }








    }






}
