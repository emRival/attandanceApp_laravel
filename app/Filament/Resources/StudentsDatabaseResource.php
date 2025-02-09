<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentsDatabaseResource\Pages;
use App\Filament\Resources\StudentsDatabaseResource\RelationManagers;
use App\Filament\Widgets\ListGalleryWidget;
use App\Filament\Widgets\TotalActiveUserCard;
use App\Models\StudentsDatabase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentsDatabaseResource extends Resource
{
    protected static ?string $model = StudentsDatabase::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static bool $isDiscovered = true;

    protected static ?string $navigationGroup = 'Database';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->columnSpanFull()
                                    ->maxLength(255),
                                Forms\Components\Select::make('class_id')
                                    ->label('Class')
                                    ->relationship('grade', 'name')
                                    ->required()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                    ])
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('teacherprofile')
                                    ->label('Profile')
                                    ->required()
                                    ->relationship('teacherprofile', 'name')
                                    ->default(
                                        '1'
                                    )
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                    ])
                                    ->placeholder('Select Profile'),
                                Forms\Components\TextInput::make('nis')
                                    ->numeric(),
                                    

                            ]),
                    ]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\ToggleButtons::make('is_active')
                                    ->label('User Status')
                                    ->boolean()
                                    ->options([
                                        '1' => 'Active',
                                        '0' => 'Inactive',
                                    ])
                                    ->default('0')
                                    ->disabled(fn(string $context, $record): bool => $context === 'create' || is_null($record?->face))
                                    ->grouped(),
                                Forms\Components\Textarea::make('face')
                                    ->disabled(),
                            ]),

                    ])
                    ->hidden(
                        fn(string $context) => $context === 'create',
                    ),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('grade.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nis')
                    ->searchable(),
                Tables\Columns\TextColumn::make('teacherprofile.name')
                ->label('Profile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('count_face_recognation')
                    ->label('Dataset')
                    ->badge()
                    ->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status')
                    ->disabled(fn($record) => is_null($record->face)),
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
    public static function getWidgets(): array
    {
        return [
            ListGalleryWidget::class,
            TotalActiveUserCard::class
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
            'index' => Pages\ListStudentsDatabases::route('/'),
            'create' => Pages\CreateStudentsDatabase::route('/create'),
            'edit' => Pages\EditStudentsDatabase::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return  strval(static::getEloquentQuery()->count());
    }
}