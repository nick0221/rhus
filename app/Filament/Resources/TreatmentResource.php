<?php

namespace App\Filament\Resources;

use App\Enums\CivilStatusesEnum;
use App\Enums\GenderEnum;
use App\Filament\Resources\TreatmentResource\Pages;
use App\Filament\Resources\TreatmentResource\RelationManagers;
use App\Models\Individual;
use App\Models\Treatment;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class TreatmentResource extends Resource
{
    protected static ?string $model = Treatment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'individual.fullname';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('')
                    ->description('Fill out all the required(*) fields')
                    ->schema([
                        Forms\Components\Select::make('individual_id')->label('Name')
                            ->relationship('individual', 'fullname', fn (Builder $query) => $query->orderByDesc('created_at'))
                            ->preload()
                            ->searchable(['firstname', 'lastname', 'middlename', 'fullname'])
                            ->columnSpan(5)
                            ->afterStateUpdated(function (Set $set, $state){
                                $ind = Individual::find($state);
                                $set('phNumber', $ind?->philhealthnum ?? '-');
                                $set('isMember', $ind?->isMember ?? false);

                            })
                            ->debounce(100)
                            ->live()
                            ->createOptionForm([
                                Forms\Components\Group::make()->schema([
                                    Forms\Components\TextInput::make('firstname')
                                        ->columnSpan(4)
                                        ->required(),
                                    Forms\Components\TextInput::make('middlename')
                                        ->columnSpan(3)
                                        ->required(),
                                    Forms\Components\TextInput::make('lastname')
                                        ->columnSpan(3)
                                        ->required(),

                                    Forms\Components\TextInput::make('extname')
                                        ->columnSpan(2),

                                    Forms\Components\Select::make('gender')
                                        ->required()
                                        ->placeholder('----')
                                        ->columnSpan(3)
                                        ->options(GenderEnum::getValues()),

                                    Forms\Components\Select::make('civilstatus')
                                        ->columnSpan(3)
                                        ->required()
                                        ->placeholder('------')
                                        ->options(CivilStatusesEnum::getValues()),

                                    Forms\Components\DatePicker::make('birthdate')
                                        ->placeholder('M d, YYYY')
                                        ->maxDate(now())
                                        ->closeOnDateSelection()
                                        ->native(false)
                                        ->required()
                                        ->suffixIcon('heroicon-o-calendar')
                                        ->columnSpan(4),

                                    Forms\Components\TextInput::make('mobile')
                                        ->autocomplete(false)
                                        ->prefix('+639')
                                        ->placeholder('9** *** ****')
                                        ->mask('999 999 9999')
                                        ->maxLength(12)
                                        ->columnSpan(4),

                                    Forms\Components\TextInput::make('height')
                                        ->numeric()
                                        ->inputMode('decimal')
                                        ->suffix('cm.')
                                        ->columnSpan(3),

                                    Forms\Components\TextInput::make('weight')
                                        ->numeric()
                                        ->inputMode('decimal')
                                        ->suffix('kg.')
                                        ->columnSpan(3),


                                ])->columns(12)


                            ])
                            ->createOptionAction(
                                fn (\Filament\Forms\Components\Actions\Action $action) => $action
                                    ->closeModalByClickingAway(false)
                                    ->modalFooterActionsAlignment('end')
                                    ->tooltip('Register new')
                                    ->modalSubmitActionLabel('Save')
                                    ->icon('heroicon-o-user-plus')
                            )
                            ->createOptionModalHeading('REGISTER NEW')
                            ->required(),

                        Forms\Components\TextInput::make('phNumber')->label('PhilHealth Number')
                            ->columnSpan(4)
                            ->columnStart(7)
                            ->default('-')
                            ->disabled(),

                        Forms\Components\Toggle::make('isMember')->label('Member')
                            ->inline(false)
                            ->columnSpan(1)
                            ->onIcon('heroicon-m-check')
                            ->disabled(),



                        Forms\Components\Select::make('category_id')
                            ->placeholder('-')
                            ->columnSpan(6)
                            ->editOptionForm([
                                Forms\Components\TextInput::make('title')->label('Category title')
                                    ->required(),
                            ])
                            ->editOptionAction(
                                fn (\Filament\Forms\Components\Actions\Action $action) => $action
                                    ->modalWidth('sm')
                                    ->modalFooterActionsAlignment('end')
                                    ->tooltip('Edit Selected Category')
                                    ->modalSubmitActionLabel('Save changes')
                            )
                            ->editOptionModalHeading('Edit Selected Category')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('title')->label('Category title')
                                    ->required(),
                            ])
                            ->createOptionAction(
                                fn (\Filament\Forms\Components\Actions\Action $action) => $action
                                    ->modalWidth('sm')
                                    ->modalFooterActionsAlignment('end')
                                    ->tooltip('Register new category')
                                    ->icon('heroicon-o-squares-plus')
                                    ->modalSubmitActionLabel('Save')
                            )
                            ->createOptionModalHeading('Add new category')
                            ->relationship('category', 'title')
                            ->preload()
                            ->searchable()
                            ->columnStart(7),

                        Forms\Components\Repeater::make('pastMedicalhistory')
                            ->label('Past Medical History')
                            ->relationship()
                            ->schema([

                                Forms\Components\DatePicker::make('historyDate')
                                    ->placeholder('M d, YYYY')
                                    ->maxDate(now())
                                    ->closeOnDateSelection()
                                    ->native(false)
                                    ->suffixIcon('heroicon-o-calendar'),

                                Forms\Components\Select::make('description')
                                    ->options([
                                        'HPN' => 'HPN',
                                        'Heart Disease' => 'Heart Disease',
                                        'Kidney Disease' => 'Kidney Disease',
                                        'Stroke/CVD' => 'Stroke/CVD',
                                        'Seisure Disorder' => 'Seisure Disorder',
                                        'Hypercholesterolemia/Dyslipidemia' => 'Hypercholesterolemia/Dyslipidemia',
                                        'Others' => 'Others'

                                    ]),

                        ])->columnSpanFull()->addable(false)->deletable(false)->columns(2),

                        Forms\Components\Repeater::make('family_histories')
                            ->label('Family History')
                            ->relationship()
                            ->schema([
                                Forms\Components\DatePicker::make('historyDate')
                                    ->placeholder('M d, YYYY')
                                    ->maxDate(now())
                                    ->closeOnDateSelection()
                                    ->native(false)
                                    ->suffixIcon('heroicon-o-calendar'),

                                Forms\Components\Select::make('description')
                                    ->options([
                                        'HPN' => 'HPN',
                                        'Heart Disease' => 'Heart Disease',
                                        'Kidney Disease' => 'Kidney Disease',
                                        'Stroke/CVD' => 'Stroke/CVD',
                                        'Seisure Disorder' => 'Seisure Disorder',
                                        'Hypercholesterolemia/Dyslipidemia' => 'Hypercholesterolemia/Dyslipidemia',
                                        'Others' => 'Others'

                                    ]),

                        ])->columnSpanFull()->addable(false)->deletable(false)->columns(2),

                        Forms\Components\Repeater::make('travel_histories')
                            ->label('Travel History')
                            ->relationship()
                            ->schema([

                                Forms\Components\DatePicker::make('dateoftravel')->label('Travel Date')
                                    ->placeholder('M d, YYYY')
                                    ->maxDate(now())
                                    ->closeOnDateSelection()
                                    ->native(false)
                                    ->columnSpan(2)
                                    ->suffixIcon('heroicon-o-calendar'),


                                Forms\Components\TextInput::make('place')
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('daysofstay')->label('Days of stay')
                                    ->numeric()
                                    ->columnSpan(1),

                        ])->columnSpanFull()->addable(false)->deletable(false)->columns(5),

                        Forms\Components\Repeater::make('ob_histories')
                            ->label('OB History')
                            ->relationship()
                            ->schema([

                                Forms\Components\DatePicker::make('lmp')->label('(LMP) Last Menstrual Period')
                                    ->closeOnDateSelection()
                                    ->date()
                                    ->columnSpan(2)
                                    ->placeholder('M d, YYYY')
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->native(false),

                                Forms\Components\TextInput::make('aog')->label('(AOG) Age of Gestation')
                                    ->columnSpan(2)
                                    ->maxLength(50),

                                Forms\Components\DatePicker::make('edc')->label('(EDC) Expected date of confinement')
                                    ->closeOnDateSelection()
                                    ->date()
                                    ->placeholder('M d, YYYY')
                                    ->native(false)
                                    ->suffixIcon('heroicon-o-calendar')
                                    ->columnSpan(3)
                                    ->maxDate(now()),


                        ])->columnSpanFull()->addable(false)->deletable(false)->columns(7)








                    ])->columns(12)->columnSpan(5),

                Forms\Components\Section::make('')->schema([
                    Forms\Components\Toggle::make('isDependent')->label('Dependent')
                        ->inline(false)
                        ->onIcon('heroicon-m-check')
                        ->live(),

                    Forms\Components\TextInput::make('phMemberName')->label('PhilHealth Member Name')
                        ->columnSpan(2)
                        ->required(fn (Get $get): bool => $get('isDependent'))
                        ->maxLength(255),

                    Forms\Components\TextInput::make('dependentPhilhealthNum')->label('Member\'s PhilHealth Number')
                        ->columnSpan(2)
                        ->numeric()
                        ->required(fn (Get $get): bool => $get('isDependent'))
                        ->maxLength(255),

                    Forms\Components\DatePicker::make('birthday')
                        ->placeholder('M d, YYYY')
                        ->maxDate(now())
                        ->closeOnDateSelection()
                        ->native(false)
                        ->required(fn (Get $get): bool => $get('isDependent'))
                        ->suffixIcon('heroicon-o-calendar')
                        ->columnSpan(2),

                    Forms\Components\TextInput::make('created_at')->visibleOn('edit')
                        ->formatStateUsing(fn ($state): string => Carbon::parse($state)->timezone('Asia/Manila')->format('M d, Y  h:iA'))
                        ->disabled()
                        ->columnSpan(2),


                      Forms\Components\TextInput::make('updated_at')->visibleOn('edit')
                          ->label('Last updated at')
                          ->formatStateUsing(fn ($state): string => Carbon::parse($state)->timezone('Asia/Manila')->format('M d, Y  h:iA'))
                          ->disabled()
                          ->columnSpan(2),


                ])->columns(1)->columnSpan(2),



            ])->columns(7);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Date Created')
                    ->date('M d, Y - h:iA')
                    ->sortable(),

                Tables\Columns\TextColumn::make('individual.fullname')->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('individual.isMember')->label('Member')
                    ->alignCenter()
                    ->boolean(),

                Tables\Columns\IconColumn::make('isDependent')->label('Dependent')
                    ->icon(fn (string $state): string => match ($state) {
                        '1' => 'heroicon-m-check-circle',
                        '0' => 'heroicon-m-x-circle',

                    })
                    ->alignCenter()
                    ->boolean(),

                Tables\Columns\TextColumn::make('category.title')->label('Category')
                    ->default('-')
                    ->sortable(),

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
            ]);
    }



    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('')
                    ->schema([
                        TextEntry::make('individual.fullname')->label('Name')
                            ->columnSpan(4)




                    ])->columns(12)->columnSpan(5),





            ])->columns(7);

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
            'view' => Pages\ViewTreatment::route('/{record}'),
        ];
    }


    /**
     * @param Model $record
     * @return string|Htmlable
     */
    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->individual->fullname;
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return TreatmentResource::getUrl('view', ['record' => $record]);
    }



}
