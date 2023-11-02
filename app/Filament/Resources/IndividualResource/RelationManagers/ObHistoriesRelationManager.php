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

class ObHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'obstetrics_histories';

    protected static ?string $modelLabel = 'OB History';


    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('lmp')->label('Last Menstrual Period(LMP)')
                    ->closeOnDateSelection()
                    ->date()
                    ->placeholder('M d, YYYY')
                    ->native(false)
                    ->columnSpanFull(),

                Forms\Components\TextInput::make('aog')->label('Age of Gestation(AOG)')
                    ->columnSpanFull()
                    ->maxLength(255),

                Forms\Components\DatePicker::make('edc')->label('Expected date of confinement(EDC)')
                    ->closeOnDateSelection()
                    ->date()
                    ->placeholder('M d, YYYY')
                    ->native(false)
                    ->columnSpanFull()
                    ->maxDate(now()),



            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('lmp')
            ->columns([
                Tables\Columns\TextColumn::make('lmp')
                    ->date('M d, Y')
                    ->label('Last Menstrual Period(LMP)'),

                Tables\Columns\TextColumn::make('aog')
                    ->label('Age of Gestation(AOG)'),

                Tables\Columns\TextColumn::make('edc')
                    ->date('M d, Y')
                    ->label('Expected date of confinement(EDC)'),

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
                    ->label('Create new OB history'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->modalHeading('Edit OB History')
                    ->modalWidth('md')
                    ->modalSubmitActionLabel('Save changes')
                    ->modalFooterActionsAlignment(Alignment::End),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ])
            ->emptyStateDescription('');
    }








}
