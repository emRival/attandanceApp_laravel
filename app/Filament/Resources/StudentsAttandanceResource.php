<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentsAttandanceResource\Pages;
use App\Filament\Resources\StudentsAttandanceResource\RelationManagers;
use App\Models\StudentsAttandance;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;

class StudentsAttandanceResource extends Resource
{
    protected static ?string $model = StudentsAttandance::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Attandance';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Select::make('student_id')
                                    ->required()
                                    ->label('Student Name ')
                                    ->relationship('student', 'name')
                                    ->searchable()
                                    ->placeholder('Select Student'),
                                Forms\Components\Select::make('times_config_id')
                                    ->label('Session')
                                    ->required()
                                    ->relationship('timeConfig', 'name')
                                    ->placeholder('Select Times Session'),
                                Forms\Components\Select::make('status')
                                    ->required()
                                    ->options([
                                        'attend' => 'Attend',
                                        'late' => 'Late',
                                        'absent' => 'Absent',
                                    ]),
                            ]),
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\DatePicker::make('date')
                                    ->required(),
                                Forms\Components\TimePicker::make('time')
                                    ->required(),
                                Forms\Components\FileUpload::make('captured_image')
                                    ->image()
                                    ->directory('students_attandance')
                                    ->visibility('private'),
                            ])
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('student.name')
                    ->label('Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.grade.name')
                    ->label('Class')
                    ->sortable(),
                Tables\Columns\TextColumn::make('timeConfig.name')
                    ->label('Session')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('time'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(function ($record) {
                        return match ($record->status) {
                            'attend' => 'success',
                            'late' => 'warning',
                            'absent' => 'danger',
                            default => 'secondary',
                        };
                    }),
                Tables\Columns\IconColumn::make('captured_image')
                    ->label('Image')
                    ->icon('heroicon-s-eye')
                    ->action(
                        Action::make('view')
                            ->label('View Image')
                            ->modalContent(function ($record) {
                                return view('actions.image_view', ['image' => $record->captured_image]);
                            })
                            ->modalWidth(MaxWidth::Medium)
                            ->modalSubmitAction(false)
                    )
            ])
            ->filters([
                SelectFilter::make('student.class_id')
                    ->label('Class')
                    ->searchable()
                    ->placeholder('Select Class')
                    ->relationship('student.grade', 'name'),
                SelectFilter::make('student_id')
                    ->label('Student')
                    ->searchable()
                    ->placeholder('Select Student')
                    ->relationship('student', 'name'),
                SelectFilter::make('times_config_id')
                    ->label('Session')
                    ->placeholder('Select Session')
                    ->relationship('timeConfig', 'name'),
                Filter::make('date')
                    ->form([
                        DatePicker::make('date_from')
                            ->default(now()->toDateString()),
                        DatePicker::make('date_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '>=', $date),
                            )
                            ->when(
                                $data['date_until'] ?? $data['date_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('date', '<=', $date),
                            );
                    })

                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['date_from'] ?? null) {
                            $indicators[] = Indicator::make('Date from ' . Carbon::parse($data['date_from'])->toFormattedDateString() . ' - ' . Carbon::parse($data['date_until'])->toFormattedDateString())
                                ->removeField('date_until');
                        }

                        return $indicators;
                    }),


            ])
            ->persistSortInSession()
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
            'index' => Pages\ListStudentsAttandances::route('/'),
            'create' => Pages\CreateStudentsAttandance::route('/create'),
            'edit' => Pages\EditStudentsAttandance::route('/{record}/edit'),
        ];
    }
}