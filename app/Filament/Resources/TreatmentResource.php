<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TreatmentResource\Pages;
use App\Filament\Resources\TreatmentResource\RelationManagers;
use App\Models\Individual;
use App\Models\Treatment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TreatmentResource extends Resource
{
    protected static ?string $model = Treatment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')
                    ->description('Fill out the required(*) fields')
                    ->schema([
                        Forms\Components\Select::make('individual_id')
                            ->relationship('individual', 'fullname')
                            ->preload()
                            ->searchable(['firstname', 'lastname', 'middlename', 'fullname'])
                            ->columnSpan(4)
                            ->afterStateUpdated(function (Set $set, $state){
                                $ind = Individual::find($state);
                                $set('phNumber', $ind?->philhealthnum ?? '-');
                                $set('isMember', $ind?->isMember ?? false);

                            })
                            ->debounce(100)
                            ->live()
                            ->required(),

                        Forms\Components\TextInput::make('phNumber')->label('PhilHealth Number')
                            ->columnSpan(3)
                            ->disabled(),

                        Forms\Components\Toggle::make('isMember')->label('Member')
                            ->inline(false)
                            ->columnSpan(3)
                            ->onIcon('heroicon-m-check')
                            ->disabled(),






                ])->columns(12)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('past_medicalhistories.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ob_histories.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('travel_histories.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('family_histories.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('individual.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTreatments::route('/'),
            'create' => Pages\CreateTreatment::route('/create'),
            'edit' => Pages\EditTreatment::route('/{record}/edit'),
        ];
    }
}
