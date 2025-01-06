<?php

namespace App\Filament\Resources\StudentsDatabaseResource\Pages;

use App\Filament\Resources\StudentsDatabaseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentsDatabase extends CreateRecord
{
    protected static string $resource = StudentsDatabaseResource::class;
}
