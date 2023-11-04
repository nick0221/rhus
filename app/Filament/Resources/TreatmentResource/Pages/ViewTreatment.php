<?php

namespace App\Filament\Resources\TreatmentResource\Pages;

use App\Filament\Resources\TreatmentResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

class ViewTreatment extends ViewRecord
{
    protected static string $resource = TreatmentResource::class;


    protected function getHeaderActions(): array
    {
        $recordsName = $this->getRecord()->individual->fullname;

        return [
            Actions\DeleteAction::make()
                ->modalHeading('Delete '. $recordsName. ' record')
                ->successNotificationTitle('Treatment record of '.$recordsName.' has been successfully deleted.'),
        ];
    }


    public function getHeading(): \Illuminate\Contracts\Support\Htmlable|string
    {
        return 'Treatment record for '.$this->getRecord()->individual->fullname;
    }


    public function getSubheading(): string|Htmlable|null
    {

        return new HtmlString('<small class="m-0 p-0" style="font-size: 0.8rem; color: #7f8c8d">Created: '.Carbon::parse($this->getRecord()->created_at)->format('M d, Y h:iA').'</small> <span style="color: #7f8c8d;">&bull;</span> <small class="m-0 p-0" style="font-size: 0.8rem; color: #7f8c8d">Updated last: '.Carbon::parse($this->getRecord()->updated_at)->format('M d, Y h:iA').'</small>');
    }


}
