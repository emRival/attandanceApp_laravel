<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeachersDatabaseResource\Pages;
use App\Filament\Widgets\ListGalleryWidget;
use App\Filament\Widgets\TotalActiveUserCard;
use App\Models\TeachersDatabase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TeachersDatabaseResource extends Resource
{
    protected static ?string $model = TeachersDatabase::class;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

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
                                    ->maxLength(255),
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
                                Forms\Components\TextInput::make('nip')
                                    ->maxLength(255)
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
                                    ->label('Face 128D Dataset')
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
                Tables\Columns\TextColumn::make('id')
                    ->label('#ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('teacherprofile.name')
                    ->label('Profile')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('count_face_recognation')
                    ->label('Dataset')
                    ->badge()
                    ->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status')
                    ->disabled(fn($record) => is_null($record->face)),

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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ListGalleryWidget::class,
            TotalActiveUserCard::class
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeachersDatabases::route('/'),
            'create' => Pages\CreateTeachersDatabase::route('/create'),
            'edit' => Pages\EditTeachersDatabase::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return  strval(static::getEloquentQuery()->count());
    }
}
