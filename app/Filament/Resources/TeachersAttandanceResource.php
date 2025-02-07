<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeachersAttandanceResource\Pages;
use App\Filament\Resources\TeachersAttandanceResource\RelationManagers;
use App\Filament\Widgets\TotalCard;
use App\Models\TeachersAttandance;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeachersAttandanceResource extends Resource
{
    protected static ?string $model = TeachersAttandance::class;
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Attendance';
    protected static int $resultsPerPageOptions = 25;  // Tambahan: Pagination options

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(['default' => 1, 'md' => 2])->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Section::make('Attendance Details')
                                ->schema([
                                    Forms\Components\Select::make('teacher_id')
                                        ->required()
                                        ->label('Teacher')
                                        ->relationship('teacher', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->disabled(fn(string $context): bool => $context === 'edit')
                                        ->columnSpanFull(),

                                    Forms\Components\Select::make('times_config_id')
                                        ->label('Session')
                                        ->required()
                                        ->relationship('timeConfig', 'name')
                                        ->disabled(fn(string $context): bool => $context === 'edit')
                                        ->columnSpanFull(),

                                    Forms\Components\Grid::make(3)->schema([
                                        Forms\Components\Select::make('status')
                                            ->required()
                                            ->options([
                                                'attend' => 'Attend',
                                                'late' => 'Late',
                                                'absent' => 'Absent',
                                            ]),

                                        Forms\Components\DatePicker::make('date')
                                            ->required()
                                            ->disabled(fn(string $context): bool => $context === 'edit')
                                            ->native(false),

                                        Forms\Components\TimePicker::make('time')
                                            ->required()
                                            ->seconds(false),
                                    ]),
                                ]),
                        ]),

                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Section::make('Image')
                                ->schema([
                                    Forms\Components\Placeholder::make('Image Preview')
                                        ->hiddenLabel()
                                        ->content(fn($record) => $record && $record->captured_image
                                            ? view('livewire.image-preview', ['image' => $record->captured_image])
                                            : 'No image available'),


                                    Forms\Components\FileUpload::make('image_upload')
                                        ->label('Upload Image')
                                        ->disk('local')
                                        ->directory('temp-uploads')
                                        ->visibility('private')
                                        ->image()
                                        ->maxSize(2048)
                                        ->reactive()
                                        ->afterStateUpdated(function ($state, $set, $record) {
                                            if (!$state) return;

                                            $base64Image = self::processImageUpload($state, $record?->id);
                                            $set('captured_image', $base64Image);

                                            // Cleanup temporary file
                                            Storage::disk('local')->delete($state->path());
                                        }),
                                ]),
                        ]),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => (
                !Auth::user()->hasRole('super_admin')
                ? $query->where('teacher_id', Auth::user()->teacher_id)
                : $query
            ))
            ->columns([
                Tables\Columns\TextColumn::make('teacher.name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('timeConfig.name')
                    ->label('Session')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),

                Tables\Columns\TextColumn::make('date')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('time')
                    ->time('H:i')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($state) => strtoupper($state))
                    ->color(fn($state) => match ($state) {
                        'attend' => 'success',
                        'late' => 'warning',
                        'absent' => 'danger',
                        default => 'gray'
                    })
                    ->toggleable(),

                Tables\Columns\IconColumn::make('captured_image')
                    ->label('Image')
                    ->icon('heroicon-m-photo')

                    ->action(
                        Tables\Actions\Action::make('view')
                            ->modalHeading('Captured Image')
                            ->modalContent(fn($record) => $record->captured_image ? view('actions.image_view', [
                                'image' => $record->captured_image
                            ]) : null)
                            ->modalWidth(MaxWidth::Medium)
                            ->modalSubmitAction(false)
                            ->disabled(
                                fn($record) => !$record->captured_image
                            )
                    )
            ])
            ->filters([
                SelectFilter::make('teacher_id')
                    ->label('Teacher')
                    ->searchable()
                    ->relationship('teacher', 'name')
                    ->hidden(fn() => !Auth::user()->hasRole('super_admin')),

                SelectFilter::make('times_config_id')
                    ->label('Session')
                    ->relationship('timeConfig', 'name'),

                Filter::make('date')
                    ->form([
                        DatePicker::make('date_from')
                            ->default(now()->toDateString()),
                        DatePicker::make('date_until')
                            ->default(now()->toDateString()),
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
            ->actions([
                Tables\Actions\EditAction::make()
                    ->iconButton()
                    ->successNotificationTitle('Attendance updated'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('date', 'desc')
            ->persistSortInSession()
            ->persistSearchInSession()
            ->persistColumnSearchesInSession();
    }

    private static function processImageUpload($file, $id): ?string
    {
        try {
            $imageData = $file->get();
            $base64Image = 'data:' . $file->getMimeType() . ';base64,' . base64_encode($imageData);

            if ($id && $attendance = TeachersAttandance::find($id)) {
                $attendance->update(['captured_image' => $base64Image]);
            }

            return $base64Image;
        } catch (\Exception $e) {
            report($e);
            return null;
        }
    }

    public static function getWidgets(): array
    {
        return [
            TotalCard::make(),
        ];
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