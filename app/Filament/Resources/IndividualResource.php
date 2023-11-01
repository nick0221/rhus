<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IndividualResource\Pages;
use App\Filament\Resources\IndividualResource\RelationManagers;
use App\Models\Individual;
use Carbon\Carbon;
use CivilStatusEnum;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
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
                        ->suffixIcon('heroicon-o-calendar')
                        ->columnStart(6)
                        ->columnSpan(2),


                   Forms\Components\Textarea::make('address')
                        ->rows(4)
                        ->columnSpan(5),

                   Forms\Components\Select::make('category_id')
                        ->selectablePlaceholder()
                        ->placeholder('-')
                        ->columnSpan(2)
                        ->createOptionForm([
                           Forms\Components\TextInput::make('title')
                               ->required(),
                        ])
                       ->createOptionAction(
                           fn (\Filament\Forms\Components\Actions\Action $action) => $action
                               ->modalWidth('sm')
                               ->modalFooterActionsAlignment('end')
                               ->tooltip('Register new category')
                               ->modalSubmitActionLabel('Save')
                       )
                        ->createOptionModalHeading('Add new category')
                        ->relationship('category', 'title')
                        ->preload()
                        ->searchable(),

                ])->columnSpan(9)->columns(7),



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

                    Forms\Components\Toggle::make('isMember')
                        ->inline(false)
                        ->onIcon('heroicon-m-check')
                        ->live(onBlur: true)
                        ->required(),

                    Forms\Components\TextInput::make('philhealthnum')->label('Philhealth Number')
                        ->numeric()
                        ->required(fn (Get $get): bool => $get('isMember'))
                        ->maxLength(255),


                ])->columnSpan(3),


            ])->columns(12);
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->deferLoading();
    }



    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Personal Information')
                    ->schema([
                        TextEntry::make('firstname')->label('Firstname')
                            ->weight(FontWeight::Bold)
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('lastname')->label('Lastname')
                            ->weight(FontWeight::Bold)
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('middlename')->label('Middlename')
                            ->weight(FontWeight::Bold)
                            ->default('-')
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('extname')->label('Ext. Name')
                            ->weight(FontWeight::Bold)
                            ->default('-')
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('gender')
                            ->weight(FontWeight::Bold)
                            ->default('-')
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('civilstatus')
                            ->weight(FontWeight::Bold)
                            ->default('-')
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('birthdate')
                            ->weight(FontWeight::Bold)
                            ->default('-')
                            ->color('info')
                            ->date('M d, Y')
                            ->columnSpan(1),

                        TextEntry::make('birthdate')->label('Age')
                            ->weight(FontWeight::Bold)
                            ->formatStateUsing(fn($state): string => Carbon::parse($state)->age)
                            ->default('-')
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('address')->label('Address')
                            ->weight(FontWeight::Bold)
                            ->color('info')
                            ->columnSpan(3),


                        TextEntry::make('category.title')->label('Category')
                            ->weight(FontWeight::Bold)
                            ->color('info')
                            ->default('-')
                            ->columnSpan(1),



                    ])->columns(4)->columnSpan(9),

                Fieldset::make('')->schema([
                    ImageEntry::make('image')
                        ->hiddenLabel()
                        ->columnSpanFull()
                        ->height('200px')
                        ->defaultImageUrl(asset('images/default-image.png')),


                    IconEntry::make('isMember')
                        ->icon(fn (string $state): string => match ($state) {
                            '1' => 'heroicon-o-check-badge',
                            '0' => 'heroicon-o-x-circle',

                        })
                        ->color(fn (string $state): string => match ($state) {
                            '1' => 'success',
                            '0' => 'danger',

                        })
                        ->default('-')
                        ->columnSpanFull(),

                    TextEntry::make('philhealthnum')->label('Philhealth Number')
                        ->default('-')
                        ->weight(FontWeight::Bold)
                        ->color('info')
                        ->columnSpanFull(),

                    TextEntry::make('height')->label('Height (cm.)')
                        ->default('-')
                        ->weight(FontWeight::Bold)
                        ->color('info')
                        ->columnSpanFull(),

                    TextEntry::make('weight')->label('Weight (kg.)')
                        ->default('-')
                        ->weight(FontWeight::Bold)
                        ->color('info')
                        ->columnSpanFull(),


                ])->columnSpan(3)


            ])->columns(12);

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
            'view' => Pages\ViewIndividual::route('/{record}'),
        ];
    }
}
