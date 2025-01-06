<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeachersDatabaseResource\Pages;
use App\Filament\Resources\TeachersDatabaseResource\RelationManagers;
use App\Filament\Resources\TeachersDatabaseResource\Widgets\ListGalleryWidget;
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
                Forms\Components\Grid::make(4)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('nip')
                            ->maxLength(255),
                        Forms\Components\ToggleButtons::make('is_active')
                            ->label('User Status')
                            ->boolean()
                            ->options([
                                '1' => 'Active',
                                '0' => 'Inactive',
                            ])
                            ->default('true')
                            ->grouped(),
                        Forms\Components\TextInput::make('position')
                            ->default('teacher')
                            ->disabled(),

                        Forms\Components\TextInput::make('face')
                            ->label('Face 128D Dataset')
                            ->disabled(),
                    ]),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nip')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
                    ->searchable(),
                Tables\Columns\TextColumn::make('count_face_recognation')
                    ->label('Dataset')
                    ->badge()
                    ->alignCenter(),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status'),
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

    public static function getRelations(): array
    {
        return [
            //
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
