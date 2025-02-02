<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeachersAttandanceResource\Pages;
use App\Filament\Resources\TeachersAttandanceResource\RelationManagers;
use App\Models\TeachersAttandance;
use Carbon\Carbon;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Auth;


class TeachersAttandanceResource extends Resource
{
    protected static ?string $model = TeachersAttandance::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Attandance';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\Select::make('teacher_id')
                                    ->required()
                                    ->label('Teachers Name ')
                                    ->relationship('teacher', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Select Teacher'),
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
                                    ->directory('teachers_attandance')
                                    ->visibility('private'),
                            ])
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                function (Builder $query) {
                    $is_super_admin = Auth::user()->hasRole('super_admin');
                    if (!$is_super_admin) {
                        $query->where('teacher_id', Auth::user()->teacher_id);
                    }
                }
            )
            ->columns([

                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('timeConfig.name')
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
                    ->icon('heroicon-m-eye')
                    ->action(
                        Action::make('view')
                            ->label('View Image')
                            ->modalContent(function ($record) {
                                // Pastikan modal memiliki konten meskipun data tidak tersedia
                                if ($record->captured_image) {
                                    return view('actions.image_view', ['image' => $record->captured_image]);
                                }
                            })
                            ->modalWidth(MaxWidth::Medium)
                            ->modalSubmitAction(false) // Nonaktifkan tombol submit jika tidak diperlukan
                    )

            ])
            ->filters([
                SelectFilter::make('teacher_id')
                    ->label('Teacher')
                    ->searchable()
                    ->placeholder('Select Teacher')
                    ->relationship('teacher', 'name'),
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
            'index' => Pages\ListTeachersAttandances::route('/'),
            'create' => Pages\CreateTeachersAttandance::route('/create'),
            'edit' => Pages\EditTeachersAttandance::route('/{record}/edit'),
        ];
    }
}