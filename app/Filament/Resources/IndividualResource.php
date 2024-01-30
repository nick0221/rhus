<?php

namespace App\Filament\Resources;

use App\Enums\CivilStatusesEnum;
use App\Enums\GenderEnum;
use App\Filament\Resources\IndividualResource\Pages;
use App\Filament\Resources\IndividualResource\RelationManagers;
use App\Models\Individual;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\IconEntry\IconEntrySize;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndividualResource extends Resource
{
    protected static ?string $model = Individual::class;

    protected static ?string $modelLabel = 'Individual Treatment';

    protected static ?string $navigationLabel = 'Patient Records';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'fullname';

    protected static ?string $pluralModelLabel = 'Patient Records';

    protected static int $globalSearchResultsLimit = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->description('Fill out all the required(*) fields.')
                    ->schema([
                        Forms\Components\TextInput::make('firstname')
                        ->autocapitalize('words')
                        ->columnSpan(2)
                        ->autocomplete(false)
                        ->required()
                        ->placeholder('Enter the firstname')
                        ->maxLength(255),

                        Forms\Components\TextInput::make('lastname')
                        ->autocapitalize('words')
                        ->columnSpan(2)
                        ->autocomplete(false)
                        ->required()
                        ->placeholder('Enter the lastname')
                        ->maxLength(255),

                        Forms\Components\TextInput::make('middlename')
                        ->autocapitalize('words')
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
                        ->options(CivilStatusesEnum::getValues()),

                        Forms\Components\Toggle::make('isMember')
                            ->inline(false)
                            ->onIcon('heroicon-m-check')
                            ->live(onBlur: true)
                            ->columnSpan(1)
                            ->required(),

                        Forms\Components\TextInput::make('philhealthnum')->label('Philhealth Number')
                            ->columnSpan(2)
                            ->numeric()
                            ->required(fn (Get $get): bool => $get('isMember'))
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('birthdate')
                        ->placeholder('M d, YYYY')
                        ->maxDate(now())
                        ->closeOnDateSelection()
                        ->native(false)
                        ->required()
                        ->suffixIcon('heroicon-o-calendar')
                        ->columnSpan(2),


                        Forms\Components\Textarea::make('placeofbirth')
                            ->rows(2)
                            ->autosize()
                            ->columnSpan(3),

                        Forms\Components\Textarea::make('address')->label('Residing Address')
                            ->rows(2)
                            ->autosize()
                            ->columnSpan(4),

                        Forms\Components\TextInput::make('occupation')
                            ->columnSpan(3),

                       Forms\Components\Fieldset::make('Guardian Information')->schema([
                            Forms\Components\TextInput::make('guardianName'),

                           Forms\Components\TextInput::make('guardianContact'),

                       ])->columnSpan(7)->columns(2),

//                       Forms\Components\Fieldset::make('Educational Attainment')->schema([
//                            Forms\Components\TextInput::make('educAttainment')->hiddenLabel()
//                               ->columnSpan(3),
//                       ])->columnSpan(4),







                    ])->columnSpan(9)->columns(7),



                Forms\Components\Section::make('')->schema([
                    Forms\Components\FileUpload::make('image')
                        ->columnSpanFull()
                        ->image()
                        ->imageEditor()
                        ->previewable()
                        ->directory('ind-pic'),

                    Forms\Components\TextInput::make('height')
                        ->numeric()
                        ->inputMode('decimal')
                        ->suffix('cm.'),
                    Forms\Components\TextInput::make('weight')
                        ->numeric()
                        ->inputMode('decimal')
                        ->suffix('kg.'),


                    Forms\Components\TextInput::make('mobile')
                        ->autocomplete(false)
                        ->prefix('+63')
                        ->placeholder('9** *** ****')
                        ->mask('999 999 9999')
                        ->maxLength(12),


                ])->columnSpan(3),





            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'DESC')
            ->columns([
                Tables\Columns\TextColumn::make('patientRef')->label('Ref#')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('isMember')->label('Ph Member')
                    ->alignCenter()
                    ->boolean(),

                Tables\Columns\ImageColumn::make('image')->label('Img')
                    ->alignCenter()
                    ->circular()
                    ->defaultImageUrl(asset('images/default-image.png'))
                    ->simpleLightbox(),

                Tables\Columns\TextColumn::make('fullname')->label('Name')
                    ->searchable(['firstname', 'middlename', 'lastname', 'fullname']),

                Tables\Columns\TextColumn::make('civilstatus')->label('Civil Status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('birthdate')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date(),
                Tables\Columns\TextColumn::make('gender')
                    ->searchable(),
                Tables\Columns\TextColumn::make('height')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('weight')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('philhealthnum')
                    ->default('-')
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
                Section::make('')

                    ->schema([
                        TextEntry::make('firstname')->label('Firstname')
                            ->formatStateUsing(fn ($state): string => ucfirst($state))
                            ->weight(FontWeight::Light)
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('lastname')->label('Lastname')
                            ->formatStateUsing(fn ($state): string => ucfirst($state))
                            ->weight(FontWeight::Light)
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('middlename')->label('Middlename')
                            ->formatStateUsing(fn ($state): string => ucfirst($state))
                            ->weight(FontWeight::Light)
                            ->default('-')
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('extname')->label('Ext. Name')
                            ->weight(FontWeight::Light)
                            ->default('-')
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('gender')
                            ->weight(FontWeight::Light)
                            ->default('-')
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('civilstatus')
                            ->weight(FontWeight::Light)
                            ->default('-')
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('birthdate')
                            ->weight(FontWeight::Light)
                            ->default('-')
                            ->color('info')
                            ->date('M d, Y')
                            ->columnSpan(1),

                        TextEntry::make('birthdate')->label('Age')
                            ->weight(FontWeight::Light)
                            ->formatStateUsing(fn($state): string => Carbon::parse($state)->age)
                            ->default('-')
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('placeofbirth')->label('Place of birth')
                            ->weight(FontWeight::Light)
                            ->color('info')
                            ->default('-')
                            ->columnSpan(2),


                        TextEntry::make('mobile')
                            ->weight(FontWeight::Light)
                            ->color('info')
                            ->default('-')
                            ->columnSpan(1),

                        TextEntry::make('occupation')
                            ->weight(FontWeight::Light)
                            ->color('info')
                            ->default('-')
                            ->columnSpan(1),


                        TextEntry::make('address')->label('Residing Address')
                            ->weight(FontWeight::Light)
                            ->color('info')
                            ->default('-')
                            ->columnSpanFull(3),



                        TextEntry::make('height')->label('Height (cm.)')
                            ->default('-')
                            ->weight(FontWeight::Light)
                            ->color('info')
                            ->columnSpan(1),

                        TextEntry::make('weight')->label('Weight (kg.)')
                            ->default('-')
                            ->weight(FontWeight::Light)
                            ->color('info')
                            ->columnSpan(1),


                        IconEntry::make('isMember')->label('Member')
                            ->icon(fn (string $state): string => match ($state) {
                                '1' => 'heroicon-o-check-badge',
                                '0' => 'heroicon-o-x-circle',

                            })
                            ->color(fn (string $state): string => match ($state) {
                                '1' => 'success',
                                '0' => 'danger',

                            })
                            ->size(IconEntrySize::ExtraLarge)
                            ->default('-')
                            ->columnSpan(1),

                        TextEntry::make('philhealthnum')->label('Philhealth Number')
                            ->default('-')
                            ->weight(FontWeight::Light)
                            ->color('info')
                            ->columnSpan(1),


                        Fieldset::make('Guardian\'s Information')->schema([
                            TextEntry::make('guardianName')->label('Guardian name')
                                ->weight(FontWeight::Light)
                                ->color('info')
                                ->default('-'),

                            TextEntry::make('guardianContact')->label('Contact')
                                ->weight(FontWeight::Light)
                                ->color('info')
                                ->default('-'),
                        ])->columns(2)->columnSpanFull(),






                    ])->columns(4)->columnSpan(9),

                Fieldset::make('')->schema([
                    ImageEntry::make('image')
                        ->hiddenLabel()
                        ->columnSpanFull()
                        ->circular()
                        ->height('240px')
                        ->simpleLightbox()
                        ->defaultImageUrl(asset('images/default-image.png')),


                    TextEntry::make('patientRef')->label('Ref#')
                        ->default('-')
                        ->weight(FontWeight::Light)
                        ->columnSpanFull()
                        ->color('info'),

                    TextEntry::make('created_at')->label('Created')
                        ->dateTime('M d, Y - h:iA')
                        ->default('-')
                        ->weight(FontWeight::Light)
                        ->columnSpanFull()
                        ->color('info'),


                    TextEntry::make('updated_at')->label('Updated last')
                        ->dateTime('M d, Y - h:iA')
                        ->default('-')
                        ->weight(FontWeight::Light)
                        ->columnSpanFull()
                        ->color('info'),





                ])->columnSpan(3)


            ])->columns(12);

    }

    public static function getRelations(): array
    {

        return [
            RelationManagers\PastMedicalhistoriesRelationManager::class,
            RelationManagers\FamilyHistoriesRelationManager::class,
            RelationManagers\TravelHistoriesRelationManager::class,
            RelationManagers\ObHistoriesRelationManager::class,
            RelationManagers\TreatmentsRelationManager::class,
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



//    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
//    {
//        return $record->firstname;
//    }
    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return IndividualResource::getUrl('view', ['record' => $record]);
    }

}
