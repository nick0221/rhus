<?php

namespace App\Filament\Resources\IndividualResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PastMedicalhistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'past_medical_histories';


//    public function isReadOnly(): bool
//    {
//        return false;
//    }



    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('historyDate')->label('Medical History date')
                    ->closeOnDateSelection()
                    ->date()
                    ->placeholder('M d, YYYY')
                    ->native(false)
                    ->columnSpanFull()
                    ->maxDate(now())
                    ->required(),

                Forms\Components\Select::make('description')
                    ->required()
                    ->columnSpanFull()
                    ->options([
                        'HPN' => 'HPN',
                        'Heart Disease' => 'Heart Disease',
                        'Kidney Disease' => 'Kidney Disease',
                        'Stroke/CVD' => 'Stroke/CVD',
                        'Seisure Disorder' => 'Seisure Disorder',
                        'Hypercholesterolemia/Dyslipidemia' => 'Hypercholesterolemia/Dyslipidemia',
                        'Others' => 'Others'

                    ])

//                Forms\Components\Textarea::make('description')
//                    ->rows(5)
//                    ->columnSpanFull()
//                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table

            ->heading('Past Medical Histories')
            ->recordTitleAttribute('description')
            ->columns([
                Tables\Columns\TextColumn::make('historyDate')
                    ->date('M d, Y'),
                Tables\Columns\TextColumn::make('description'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
//                Tables\Actions\CreateAction::make()
//                    ->icon('heroicon-o-plus')
//                    ->modalWidth('md')
//                    ->modalSubmitActionLabel('Save')
//                    ->closeModalByClickingAway(false)
//                    ->modalFooterActionsAlignment(Alignment::End)
//                    ->createAnother(false)
//                    ->label('Create new medical history'),
            ])
            ->actions([
//                Tables\Actions\EditAction::make()
//                    ->modalHeading('Edit Past Medical History')
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
            ])
            ->emptyStateHeading('No past medical history found.');
    }
}
