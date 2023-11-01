<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndividualResource\Pages;
use App\Filament\Resources\IndividualResource\RelationManagers;
use App\Models\Individual;
use CivilStatusEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn\IconColumnSize;
use Filament\Tables\Table;
use GenderEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndividualResource extends Resource
{
    protected static ?string $model = Individual::class;


    protected static ?string $modelLabel = 'Individual Treatment';

    protected static ?string $navigationLabel = 'Individuals';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->description('Fill out all the required fields.')
                    ->schema([
                    Forms\Components\TextInput::make('firstname')
                        ->columnSpan(2)
                        ->autocomplete(false)
                        ->required()
                        ->placeholder('Enter the firstname')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('lastname')
                        ->columnSpan(2)
                        ->autocomplete(false)
                        ->required()
                        ->placeholder('Enter the lastname')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('middlename')
                        ->columnSpan(2)
                        ->autocomplete(false)
                        ->placeholder('Enter the middlename')
                        ->maxLength(255),

                    Forms\Components\TextInput::make('extname')
                        ->columnSpan(1)
                        ->autocomplete(false)
                        ->placeholder('e.g(Jr.) ')
                        ->maxLength(255),

                    Forms\Components\Select::make('gender')
                        ->options(GenderEnum::getValues())
                        ->placeholder('------')
                        ->columnSpan(2)
                        ->required(),

                    Forms\Components\Select::make('civilstatus')
                        ->columnSpan(2)
                        ->required()
                        ->placeholder('------')
                        ->options(CivilStatusEnum::getValues()),

                    Forms\Components\DatePicker::make('birthdate')
                        ->placeholder('M d, YYYY')
                        ->maxDate(now())
                        ->closeOnDateSelection()
                        ->native(false)
                        ->required()
                        ->columnStart(6)
                        ->columnSpan(2),


                   Forms\Components\Textarea::make('address')
                       ->rows(5)
                       ->columnSpanFull(),


                   Forms\Components\Toggle::make('isMember')
                       ->inline(false)
                       ->columnSpan(2)
                       ->onIcon('heroicon-m-check')
                       ->live(onBlur: true)
                       ->required(),

                   Forms\Components\TextInput::make('philhealthnum')->label('Philhealth Number')
                       ->columnSpan(3)
                       ->columnStart(3)
                       ->numeric()
                       ->required(fn (Get $get): bool => $get('isMember'))
                       ->maxLength(255),





                ])->columnSpan(4)->columns(7),



                Forms\Components\Section::make('')->schema([
                    Forms\Components\FileUpload::make('image')
                        ->extraAttributes(['class' => 'mb-7'])
                        ->columnSpanFull()
                        ->image(),

                    Forms\Components\TextInput::make('height')
                        ->numeric()
                        ->inputMode('decimal')
                        ->suffix('cm.')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('weight')
                        ->numeric()
                        ->inputMode('decimal')
                        ->suffix('kg.')
                        ->maxLength(255),
                ])->columnSpan(1),


            ])->columns(5);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('isMember')
                    ->alignStart()
                    ->boolean(),

                Tables\Columns\TextColumn::make('fullname')
                    ->searchable(),

                Tables\Columns\TextColumn::make('civilstatus')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthdate')
                    ->date(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('height')
                    ->searchable(),
                Tables\Columns\TextColumn::make('weight')
                    ->searchable(),
                Tables\Columns\TextColumn::make('philhealthnum')
                    ->searchable(),

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
            ])
            ->deferLoading();
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
            'index' => Pages\ListIndividuals::route('/'),
            'create' => Pages\CreateIndividual::route('/create'),
            'edit' => Pages\EditIndividual::route('/{record}/edit'),
        ];
    }
}
