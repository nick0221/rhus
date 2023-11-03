<?php

namespace App\Filament\Resources\IndividualResource\Pages;

use App\Filament\Resources\IndividualResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\HtmlString;

class ViewIndividual extends ViewRecord
{
    protected static string $resource = IndividualResource::class;


    /**
     * @return string|\Illuminate\Contracts\Support\Htmlable
     */
    public function getHeading(): \Illuminate\Contracts\Support\Htmlable|string
    {
        return $this->getRecord()->fullname.' Profile';
    }

    public function getSubheading(): string|Htmlable|null
    {
        $isMember = ($this->getRecord()->isMember == true) ? 'Yes' : 'No';
        $phNumber = (empty($this->getRecord()->philhealthnum)) ? ' ' : $this->getRecord()->philhealthnum ;
        return new HtmlString('<small class="m-0 p-0 text-blue-900">Ph. Member: '.$isMember.'</small> &bull; <small class="m-0 p-0">Ph. Number: '.$phNumber.'</small>');
    }

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
