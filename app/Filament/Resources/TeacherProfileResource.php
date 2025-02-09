<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeacherProfileResource\Pages;
use App\Filament\Resources\TeacherProfileResource\RelationManagers;
use App\Models\TeacherProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TeacherProfileResource extends Resource
{
    protected static ?string $model = TeacherProfile::class;
    protected static ?string $label = "Profiles";
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->columnSpanFull(),
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
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn($record) => $record->id !== 1), // ID 1 tidak bisa diedit

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Hapus Data Terpilih')
                        ->deselectRecordsAfterCompletion()
                        ->before(function ($records) {
                            // Cek apakah ada record dengan ID 1
                            if ($records->where('id', 1)->isNotEmpty()) {
                                Notification::make()
                                    ->title('Tidak Dapat Dihapus')
                                    ->body('Data `General` tidak boleh dihapus!')
                                    ->danger()
                                    ->persistent()
                                    ->send();

                                // Membatalkan seluruh proses penghapusan
                                return false;
                            }
                            return $records;
                        })
                        ->action(function ($records) {
                            // Filter records untuk memastikan ID 1 tidak terhapus
                            $filteredRecords = $records->filter(fn($record) => $record->id !== 1);

                            if ($filteredRecords->isNotEmpty()) {
                                static::getModel()::destroy($filteredRecords->pluck('id'));

                                Notification::make()
                                    ->title('Profile Terhapus')
                                    ->body("Data profile berhasil dihapus, Semua data yang terkait dengan profile yang terhapus akan beruah menjadi 'General'.")
                                    ->success()
                                    ->persistent()
                                    ->send();
                            }
                        })
                ]),
            ]);;
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
            'index' => Pages\ListTeacherProfiles::route('/'),
            'create' => Pages\CreateTeacherProfile::route('/create'),
            'edit' => Pages\EditTeacherProfile::route('/{record}/edit'),
        ];
    }
}