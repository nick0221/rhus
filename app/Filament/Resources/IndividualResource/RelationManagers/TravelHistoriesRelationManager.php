<?php

namespace App\Filament\Resources\IndividualResource\RelationManagers;

use Faker\Provider\Text;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TravelHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'travel_histories';


//    public function isReadOnly(): bool
//    {
//        return false;
//    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('dateoftravel')->label('Travel Date')
                    ->closeOnDateSelection()
                    ->date()
                    ->placeholder('M d, YYYY')
                    ->native(false)
                    ->columnSpanFull()
                    ->maxDate(now())
                    ->required(),

                Forms\Components\TextInput::make('place')
                    ->columnSpanFull()
                    ->required(),

                Forms\Components\TextInput::make('daysofstay')->label('How long or Days of Stay')
                    ->columnSpanFull()
                    ->numeric()
                    ->required(),



            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('dateoftravel')
            ->columns([
                Tables\Columns\TextColumn::make('dateoftravel')->label('Traveled Date')->date('M d, Y'),
                Tables\Columns\TextColumn::make('place'),
                Tables\Columns\TextColumn::make('daysofstay')->label('Days of Stay'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make()
//                    ->icon('heroicon-o-plus')
//                    ->modalWidth('md')
//                    ->modalSubmitActionLabel('Save')
//                    ->modalFooterActionsAlignment(Alignment::End)
//                    ->createAnother(false)
//                    ->closeModalByClickingAway(false)
//                    ->successNotificationTitle('Travel history has been successfully added.')
//                    ->label('Create new travel history'),
            ])
            ->actions([
//                Tables\Actions\EditAction::make()
//                    ->modalHeading('Edit Travel History')
//                    ->modalWidth('md')
//                    ->closeModalByClickingAway(false)
//                    ->modalSubmitActionLabel('Save changes')
//                    ->modalFooterActionsAlignment(Alignment::End),
//
//                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }
}
