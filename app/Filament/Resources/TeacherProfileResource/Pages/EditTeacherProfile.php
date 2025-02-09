<?php

namespace App\Filament\Resources\TeacherProfileResource\Pages;

use App\Filament\Resources\TeacherProfileResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Http\RedirectResponse;

class EditTeacherProfile extends EditRecord
{
    protected static string $resource = TeacherProfileResource::class;

    public function mount($record): void
    {
        parent::mount($record);

        if ($this->record->id === 1) {
            Notification::make()
                ->title('Akses Ditolak')
                ->body("Profile 'General' tidak bisa diedit.")
                ->danger()
                ->persistent()
                ->send();

            $this->redirect(TeacherProfileResource::getUrl('index'));
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}