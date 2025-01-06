<?php

namespace App\Filament\Resources\TeachersDatabaseResource\Pages;

use App\Filament\Resources\TeachersDatabaseResource;
use App\Filament\Resources\TeachersDatabaseResource\Widgets\ListGalleryWidget;
use App\Models\TeachersDatabase;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditTeachersDatabase extends EditRecord
{
    protected static string $resource = TeachersDatabaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    protected function getFooterWidgets(): array
    {
        return [
            ListGalleryWidget::make([
                'record' => $this->record,
            ])
        ];
    }
}