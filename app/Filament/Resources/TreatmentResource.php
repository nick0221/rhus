<?php

namespace App\Filament\Resources;

use App\Enums\CivilStatusesEnum;
use App\Enums\GenderEnum;
use App\Filament\Resources\TreatmentResource\Pages;
use App\Filament\Resources\TreatmentResource\RelationManagers;
use App\Models\FollowupCheckup;
use App\Models\Individual;
use App\Models\Treatment;
use Carbon\Carbon;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use Symfony\Component\Finder\Iterator\DateRangeFilterIterator;

class TreatmentResource extends Resource
{
    protected static ?string $model = Treatment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $recordTitleAttribute = 'individual.fullname';

    protected static ?string $pluralModelLabel = 'Treatment Records';


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
                                        ->date()
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

                                    Forms\Components\Toggle::make('isMember')
                                        ->inline(false)
                                        ->onIcon('heroicon-m-check')
                                        ->live(onBlur: true)
                                        ->columnSpan(2)
                                        ->columnStart(1)
                                        ->required(),

                                    Forms\Components\TextInput::make('philhealthnum')->label('Philhealth Number')
                                        ->columnSpan(4)
                                        ->numeric()
                                        ->required(fn (Get $get): bool => $get('isMember'))
                                        ->maxLength(255),



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
                            ->createOptionModalHeading('REGISTER NEW PATIENT')
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

                        Forms\Components\TextInput::make('attendingProvider')
                            ->placeholder('Enter the attending provider...')
                            ->required()
                            ->columnSpan(6),


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

                        Forms\Components\Repeater::make('pastMedicalhistory')->hiddenLabel()
                            ->itemLabel('1. Past Medical History')
                            ->relationship()
                            ->schema([

                                Forms\Components\DatePicker::make('historyDate')
                                    ->date()
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

                        ])->columnSpanFull()->addable(false)->deletable(false)->columns(2)->collapsible()->collapsed(),

                        Forms\Components\Repeater::make('family_histories')->hiddenLabel()
                            ->itemLabel('2. Family History')
                            ->relationship()
                            ->schema([
                                Forms\Components\DatePicker::make('historyDate')
                                    ->date()
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

                        ])->columnSpanFull()->addable(false)->deletable(false)->columns(2)->collapsible()->collapsed(),

                        Forms\Components\Repeater::make('travel_histories')->hiddenLabel()
                            ->itemLabel('3. Travel History')
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

                        ])->columnSpanFull()->addable(false)->deletable(false)->columns(5)->collapsible()->collapsed(),

                        Forms\Components\Repeater::make('ob_histories')->hiddenLabel()
                            ->itemLabel('4. OB History')
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


                        ])->columnSpanFull()->addable(false)->deletable(false)->columns(7)->collapsible()->collapsed(),

                        Forms\Components\Repeater::make('vitalSign')->hiddenLabel()
                            ->itemLabel('5. Vital Signs')
                            ->schema([

                                Forms\Components\TextInput::make('bloodPressure')->label('BP:')->inlineLabel()
                                    ->columnSpan(4)
                                    ->suffix('mmHg')
                                    ->maxLength(15),

                                Forms\Components\TextInput::make('rr')->label('RR:')->inlineLabel()
                                    ->columnSpan(4)
                                    ->suffix('cpm')
                                    ->maxLength(15),

                                Forms\Components\TextInput::make('temp')->label('Temp:')->inlineLabel()
                                    ->columnSpan(4)
                                    ->suffix('C')
                                    ->maxLength(15),

                                Forms\Components\TextInput::make('hr')->label('HR:')->inlineLabel()
                                    ->columnSpan(4)
                                    ->suffix('bpm')
                                    ->maxLength(15),

                                Forms\Components\TextInput::make('pr')->label('PR:')->inlineLabel()
                                    ->columnSpan(4)
                                    ->suffix('bpm')
                                    ->maxLength(15),


                            ])->orderColumn(false)->columnSpanFull()->addable(false)->deletable(false)->columns(7)->collapsible()->collapsed(false),



                        Forms\Components\Textarea::make('diagnosis')
                            ->placeholder('Enter diagnosis...')
                            ->rows(6)
                            ->autosize()
                            ->columnSpan(6),

                        Forms\Components\Textarea::make('medication')
                            ->placeholder('Enter the medication...')
                            ->rows(6)
                            ->autosize()
                            ->columnSpan(6),

                        Forms\Components\Textarea::make('chiefComplaints')
                            ->placeholder('Enter the Chief Complaints...')
                            ->rows(6)
                            ->autosize()
                            ->columnSpan(12),



                        Forms\Components\Repeater::make('followupCheckup')->hiddenLabel()
                            ->deletable(false)
                            ->itemLabel('Follow-up Checkup Details(If applicable)')
                            ->relationship()
                            ->schema([
                                Forms\Components\DateTimePicker::make('followupDate')
                                    ->native(false)
                                    ->minutesStep(10)
                                    ->seconds(false)
                                    ->minDate(now())
                                    ->displayFormat('m/d/Y h:i A')
                                    ->placeholder('mm/dd/yyyy hh:mm')
                                    ->timezone('Asia/Manila'),

                                Forms\Components\Textarea::make('remarksNote')
                                    ->rows(6)
                                    ->autosize(),





                            ])->columnSpan(12)->maxItems(1),



                    ])->columns(12)->columnSpan(5),



                Forms\Components\Section::make('')->schema([
                    Forms\Components\Toggle::make('isDependent')->label('Dependent')
                        ->inline(false)
                        ->onIcon('heroicon-m-check')
                        ->live(),

                    Forms\Components\TextInput::make('phMemberName')->label('PhilHealth Member Name')
                        ->columnSpan(2)
                        ->required(fn (Get $get): bool => $get('isDependent'))
                        ->disabled(fn (Get $get): bool => $get('isDependent') == 0)
                        ->maxLength(255),

                    Forms\Components\TextInput::make('dependentPhilhealthNum')->label('Member\'s PhilHealth Number')
                        ->columnSpan(2)
                        ->numeric()
                        ->disabled(fn (Get $get): bool => $get('isDependent') == 0)
                        ->required(fn (Get $get): bool => $get('isDependent'))
                        ->maxLength(255),

                    Forms\Components\DatePicker::make('birthday')
                        ->date()
                        ->placeholder('M d, YYYY')
                        ->maxDate(now())
                        ->closeOnDateSelection()
                        ->native(false)
                        ->disabled(fn (Get $get): bool => $get('isDependent') == 0)
                        ->required(fn (Get $get): bool => $get('isDependent'))
                        ->suffixIcon('heroicon-o-calendar')
                        ->columnSpan(2),

                    Forms\Components\DatePicker::make('created_at')->visibleOn('edit')
                        ->formatStateUsing(fn ($state): string => Carbon::parse($state)->timezone('Asia/Manila')->format('M d, Y h:i A'))
                        ->disabled()
                        ->columnSpan(2),


                      Forms\Components\DatePicker::make('updated_at')->visibleOn('edit')
                          ->formatStateUsing(fn ($state): string => Carbon::parse($state)->timezone('Asia/Manila')->format('M d, Y h:i A'))
                          ->disabled()
                          ->columnSpan(2),


                ])->columns(1)->columnSpan(2),




            ])->columns(7);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'DESC')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Date Created')
                    ->date('M d, Y h:iA')
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


                Tables\Columns\TextColumn::make('followupCheckup.followupDate')->label('Followup Date')
                    ->date('M d, Y - h:i A')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('followupCheckup.followupStatus')->label('Status')
                    ->badge(function (Treatment $record){
                        if($record->followupCheckup[0]->followupStatus >  0){
                            return true;
                        }else{
                            return false;
                        }

                    })
                    ->color(fn (string $state): string => match ($state) {
                        '3' => 'warning',
                        '1' => 'success',
                        '2' => 'danger',
                        '0' => '',
                    })
                    ->alignCenter()
                    ->formatStateUsing(fn (string $state): string => match ($state){
                        '0' => '-',
                        '1' => 'Done',
                        '2' => 'Not showed',
                        '3' => 'To follow',
                    })
                    ->sortable(),

                //Tables\Columns\TextColumn::make('vitalSign')
                 //   ->formatStateUsing(fn (array $state) => dump($state))
                Tables\Columns\TextColumn::make('followupCheckup.remarksNote')->label('Notes/Remarks'),
                Tables\Columns\TextColumn::make('diagnosis')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('medication')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('chiefComplaints')
                    ->toggleable(isToggledHiddenByDefault: true),





            ])
            ->filters([
                DateRangeFilter::make('created_at')->label('Date Created')
                    ->placeholder('Select date range')
                    ->withIndicator()
                    ->useRangeLabels()
                    ->timezone('Asia/Manila')
                    ->setAutoApplyOption(true),



                Filter::make('followup')
                    ->form([
                        DatePicker::make('checkupDate')->label('Followup Checkup')
                            ->placeholder('Select date')
                            ->closeOnDateSelection()
                            ->format('Y-m-d')
                            ->native(false),

                    ])
                    ->baseQuery(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['checkupDate'],
                                fn (Builder $query, $date): Builder =>  $query
                                    ->select('treatments.*', 'followupStatus', 'followupDate')
                                    ->whereDate('followup_checkups.followupDate', '=', $date)
                                    ->leftjoin('followup_checkups', 'followup_checkups.treatment_id', '=', 'treatments.id'),
                            );

                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (! $data['checkupDate']) {
                            return null;
                        }

                        return 'Followup Checkup: ' . Carbon::parse($data['checkupDate'])->toFormattedDateString();
                    }),

                Tables\Filters\SelectFilter::make('followupStat')->label('Followup Status')
                     ->options([
                         '1' => 'Done',
                         '2' => 'Not Show',
                         '3' => 'To Follow',
                     ])
                     ->modifyQueryUsing(function (Builder $query, $state) {
                         if (! $state['value']) {
                             return $query;
                         }
                         return $query->whereHas('followupCheckup', fn($query) => $query->where('followupStatus', $state['value']));
                     }),


            ], layout: FiltersLayout::AboveContent)->filtersFormColumns(3)


            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('markdone')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->label('Mark as done')
                        ->requiresConfirmation()
                        ->closeModalByClickingAway(false)
                        ->hidden(function (Treatment $record){
                            if($record->followupCheckup[0]->followupStatus == 1){
                                return true;
                            }else{
                                return false;
                            }

                        })
                        ->action(function (Treatment $record){
                            $record->markDone($record);
                        })

                ])->tooltip('See actions'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
//                    ExportBulkAction::make()->exports([
//                        ExcelExport::make()->fromTable()
//                            ->withColumns([
//                                Column::make('individual.isMember')->heading('Is Member')
//                                    ->formatStateUsing(fn ($state) => ($state === 1) ? 'Yes':'No'),
//
//                                Column::make('isDependent')->heading('Is Dependent')
//                                    ->formatStateUsing(fn ($state) => ($state === 1) ? 'Yes':'No'),
//
//                                Column::make('individual.philhealthnum')->heading('Phil Health Number'),
//                                Column::make('vitalSign')->heading('Vitals'),
//                                Column::make('diagnosis')->heading('Diagnosis'),
//                                Column::make('medication')->heading('Medication'),
//                                Column::make('chiefComplaints')->heading('Chief Complaints'),
//                                Column::make('attendingProvider')->heading('Attending Provider'),
//                                Column::make('dependentPhilHealthNum')->heading('Dependent\'s Phil Health Number (if Dependent)'),
//                                Column::make('birthday')->heading('Dependent\'s Birthday'),
//                                Column::make('phMemberName')->heading('Dependent\'s Member Name'),
//
//                            ])
//                            ->except(['individuals.create_at', 'individuals.id', 'treatments.id', 'name', 'id'])
//                            ->withFilename(fn () => 'ExportedTreatmentRecords-'.now()->toDateString())
//                            ->modifyQueryUsing(fn ($query) => $query
//                                ->select('treatments.*', 'individuals.*', )
//                                ->leftjoin('followup_checkups', 'followup_checkups.treatment_id', '=', 'treatments.id')
//                                ->leftjoin('individuals', 'individuals.id', '=', 'treatments.individual_id')
//                            )
//                    ])
//                    Tables\Actions\BulkAction::make('markDone')
//                        ->icon('heroicon-o-check-circle')
//                        ->color('success')
//                        ->label('Mark the selected done')
//                        ->action(function (Collection $records){
//                            $records->each(function ($record){
//                                $record->followupCheckup[0]->followupStatus = 1;
//                                $record->save();
//                            });
//                        })
//                        ->requiresConfirmation()
//                        ->deselectRecordsAfterCompletion(),
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
                            ->default('-')
                            ->weight(FontWeight::Light)
                            ->color('info')
                            ->columnSpan(5),

                        TextEntry::make('individual.philhealthnum')->label('PhilHealth Number')
                            ->default('-')
                            ->formatStateUsing(fn($state): string => (empty($state)) ? '-': $state)
                            ->weight(FontWeight::Light)
                            ->color('info')
                            ->columnSpan(5),

                        IconEntry::make('individual.isMember')->label('Member')
                            ->default('-')
                            ->icon(fn (string $state): string => match ($state) {
                                '1' => 'heroicon-o-check-badge',
                                '0' => 'heroicon-o-x-circle',

                            })
                            ->color(fn (string $state): string => match ($state) {
                                '1' => 'success',
                                '0' => 'danger',

                            })
                            ->columnSpan(2),

                        TextEntry::make('attendingProvider')
                            ->default('-')
                            ->weight(FontWeight::Light)
                            ->color('info')
                            ->columnSpan(7),


                        TextEntry::make('category.title')
                            ->default('-')
                            ->weight(FontWeight::Light)
                            ->color('info')
                            ->columnSpan(3),


                        Fieldset::make('Past Medical History')
                            ->relationship('pastMedicalhistory')
                            ->schema([
                                TextEntry::make('historyDate')
                                    ->date('M d, Y')
                                    ->default('-')
                                    ->formatStateUsing(fn($state): string => (empty($state)) ? '-': $state)
                                    ->weight(FontWeight::Light)
                                    ->color('info'),

                                TextEntry::make('description')

                                    ->default('-')
                                    ->formatStateUsing(fn($state): string => (empty($state)) ? '-': $state)
                                    ->weight(FontWeight::Light)
                                    ->color('info'),

                        ]),

                        Fieldset::make('Family History')
                            ->relationship('family_histories')
                            ->schema([
                                TextEntry::make('historyDate')
                                    ->date('M d, Y')
                                    ->default('-')
                                    ->formatStateUsing(fn($state): string => (empty($state)) ? '-': $state)
                                    ->weight(FontWeight::Light)
                                    ->color('info'),

                                TextEntry::make('description')
                                    ->default('-')
                                    ->formatStateUsing(fn($state): string => (empty($state)) ? '-': $state)
                                    ->weight(FontWeight::Light)
                                    ->color('info'),

                        ]),

                        Fieldset::make('Travel History')
                            ->relationship('travel_histories')
                            ->schema([
                                TextEntry::make('dateoftravel')->label('Travel Date')
                                    ->date('M d, Y')
                                    ->default('-')
                                    ->formatStateUsing(fn($state): string => (empty($state)) ? '-': $state)
                                    ->weight(FontWeight::Light)
                                    ->color('info'),

                                TextEntry::make('place')
                                    ->default('-')
                                    ->formatStateUsing(fn($state): string => (empty($state)) ? '-': $state)
                                    ->weight(FontWeight::Light)
                                    ->color('info'),

                                TextEntry::make('daysofstay')->label('How long/days of stay')
                                    ->default('-')
                                    ->formatStateUsing(fn($state): string => (empty($state)) ? '-': $state)
                                    ->weight(FontWeight::Light)
                                    ->color('info'),

                            ])->columns(3),

                        Fieldset::make('OB History')
                            ->relationship('ob_histories')
                            ->schema([
                                TextEntry::make('lmp')->label('(LMP) Last Menstrual Period')
                                    ->default('-')
                                    ->formatStateUsing(fn($state): string => (empty($state)) ? '-': $state)
                                    ->weight(FontWeight::Light)
                                    ->color('info'),

                                TextEntry::make('aog')->label('(AOG) Age of Gestation')
                                    ->default('-')
                                    ->formatStateUsing(fn($state): string => (empty($state)) ? '-': $state)
                                    ->weight(FontWeight::Light)
                                    ->color('info'),

                                TextEntry::make('edc')->label('(EDC) Expected date of confinement')
                                    ->default('-')
                                    ->formatStateUsing(fn($state): string => (empty($state)) ? '-': $state)
                                    ->weight(FontWeight::Light)
                                    ->color('info'),



                        ])->columns(3),


                        Fieldset::make('Vital Signs')->schema([
                            RepeatableEntry::make('vitalSign')->hiddenLabel()
                                ->schema([
                                    TextEntry::make('bloodPressure')->label('BP: ')
                                        ->formatStateUsing(fn($state): string => (empty($state)) ? '-': "{$state} mmHg")
                                        ->inlineLabel()
                                        ->weight(FontWeight::Light)
                                        ->color('info'),

                                    TextEntry::make('rr')->label('RR: ')
                                        ->formatStateUsing(fn($state): string => (empty($state)) ? '-': "{$state} cpm")
                                        ->inlineLabel()
                                        ->weight(FontWeight::Light)
                                        ->color('info'),

                                    TextEntry::make('temp')->label('Temp: ')
                                        ->formatStateUsing(fn($state): string => (empty($state)) ? '-': "{$state} C")
                                        ->inlineLabel()
                                        ->weight(FontWeight::Light)
                                        ->color('info'),

                                    TextEntry::make('hr')->label('HR: ')
                                        ->formatStateUsing(fn($state): string => (empty($state)) ? '-': "{$state} bpm")
                                        ->inlineLabel()
                                        ->weight(FontWeight::Light)
                                        ->color('info'),

                                    TextEntry::make('pr')->label('PR: ')
                                        ->formatStateUsing(fn($state): string => (empty($state)) ? '-': "{$state} bpm")
                                        ->inlineLabel()
                                        ->weight(FontWeight::Light)
                                        ->color('info'),

                                ])->columns(1)->columnSpanFull()->contained(false),
                        ]),

                        Fieldset::make('Diagnosis')->schema([
                            TextEntry::make('diagnosis')->hiddenLabel()
                                ->weight(FontWeight::Light)
                                ->color('info'),
                        ])->columnSpan(6),

                        Fieldset::make('Medication')->schema([
                            TextEntry::make('medication')->hiddenLabel()
                                ->weight(FontWeight::Light)
                                ->color('info'),
                        ])->columnSpan(6),


                        Fieldset::make('Chief Complaints')->schema([
                            TextEntry::make('chiefComplaints')->hiddenLabel()
                                ->weight(FontWeight::Light)
                                ->color('info'),
                        ])->columnSpan(12),



                        Fieldset::make('Followup details')->schema([
                            RepeatableEntry::make('followupCheckup')->hiddenLabel()

                                ->schema([
                                    TextEntry::make('followupDate')->label('Followup date')
                                        ->weight(FontWeight::Light)
                                        ->dateTime('M d, Y - h:i A')
                                        ->color('info'),

                                    TextEntry::make('remarksNote')->label('Remarks/Notes')
                                        ->weight(FontWeight::Light)
                                        ->color('info'),

                                    TextEntry::make('followupStatus')->label('Status')
                                        ->color(fn (string $state): string => match ($state) {
                                            '3' => 'warning',
                                            '1' => 'success',
                                            '2' => 'danger',
                                            '0' => '',
                                        })
                                        ->formatStateUsing(fn (string $state): string => match ($state){
                                            '3' => 'To follow',
                                            '1' => 'Done',
                                            '2' => 'Not showed',
                                            '0' => '',
                                        })
                                        ->default('-')
                                        ->badge()
                                        ->weight(FontWeight::Light),




                                ])->columnSpanFull()
                        ])->columnSpan(12),





                    ])->columns(12)->columnSpan(5),



                Section::make('')->schema([
                    IconEntry::make('isDependent')->label('Dependent')
                        ->default('-')
                        ->icon(fn (string $state): string => match ($state) {
                            '1' => 'heroicon-o-check-badge',
                            '0' => 'heroicon-o-x-circle',

                        })
                        ->color(fn (string $state): string => match ($state) {
                            '1' => 'success',
                            '0' => 'danger',

                        })
                        ->columnSpanFull(),

                    TextEntry::make('phMemberName')->label('Member\'s Name')
                        ->columnSpanFull()
                        ->default('-')
                        ->weight(FontWeight::Light)
                        ->color('info'),

                    TextEntry::make('dependentPhilhealthNum')->label('Member\'s PhilHealth Number')
                        ->columnSpanFull()
                        ->default('-')
                        ->weight(FontWeight::Light)
                        ->color('info'),

                    TextEntry::make('birthday')->label('Birthdate')
                        ->weight(FontWeight::Light)
                        ->color('info')
                        ->date('M d, Y')
                        ->columnSpanFull(1),



                ])->columns(1)->columnSpan(2),






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
