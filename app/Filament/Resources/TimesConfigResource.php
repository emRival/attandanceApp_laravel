<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TimesConfigResource\Pages;
use App\Filament\Resources\TimesConfigResource\RelationManagers;
use App\Models\TimesConfig;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\NumberInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TimesConfigResource extends Resource
{
    protected static ?string $model = TimesConfig::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Settings';

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
                                Forms\Components\TimePicker::make('start')
                                    ->label('Start')
                                    ->seconds(false)
                                    ->required(),
                                Forms\Components\TimePicker::make('end')
                                    ->label('End')
                                    ->seconds(false)
                                    ->required(),
                                Forms\Components\TextInput::make('late')
                                    ->label('Late on minutes')
                                    ->numeric()
                                    ->default(0),
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

                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start'),
                Tables\Columns\TextColumn::make('end'),
                Tables\Columns\TextColumn::make('late')
                    ->label('Late (minutes)')
                    ->suffix(' Minutes'),
                Tables\Columns\TextColumn::make('teacherprofile.name')
                    ->label('Profile')
                    ->searchable(),
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
            'index' => Pages\ListTimesConfigs::route('/'),
            'create' => Pages\CreateTimesConfig::route('/create'),
            'edit' => Pages\EditTimesConfig::route('/{record}/edit'),
        ];
    }
}