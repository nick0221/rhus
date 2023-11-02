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

class FamilyHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'family_histories';

    public function isReadOnly(): bool
    {
        return false;
    }


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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
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
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus')
                    ->modalWidth('md')
                    ->modalSubmitActionLabel('Save')
                    ->modalFooterActionsAlignment(Alignment::End)
                    ->createAnother(false)
                    ->label('Create new family history'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
