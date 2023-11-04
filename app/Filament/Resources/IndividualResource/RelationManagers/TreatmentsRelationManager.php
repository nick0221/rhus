<?php

namespace App\Filament\Resources\IndividualResource\RelationManagers;

use App\Models\Treatment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TreatmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'treatmentRecords';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
//                Forms\Components\TextInput::make('individual_id')
//                    ->required()
//                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {


        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->date('M d, Y'),

                Tables\Columns\TextColumn::make('category.title')
                    ->default('-'),

                Tables\Columns\TextColumn::make('attendingProvider')
                    ->default('-'),

                Tables\Columns\TextColumn::make('diagnosis')
                    ->default('-'),

                Tables\Columns\TextColumn::make('medication')
                    ->default('-'),

                Tables\Columns\TextColumn::make('chiefComplaints')
                    ->default('-'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([

            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }
}
