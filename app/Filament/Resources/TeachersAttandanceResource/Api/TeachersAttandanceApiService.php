<?php

namespace App\Filament\Resources\TeachersAttandanceResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\TeachersAttandanceResource;
use Illuminate\Routing\Router;
use App\Filament\Resources\TeachersAttandanceResource\Api\Handlers\CreateHandler;
use App\Filament\Resources\TeachersAttandanceResource\Api\Handlers\UpdateHandler;
use App\Filament\Resources\TeachersAttandanceResource\Api\Handlers\DeleteHandler;
use App\Filament\Resources\TeachersAttandanceResource\Api\Handlers\PaginationHandler;
use App\Filament\Resources\TeachersAttandanceResource\Api\Handlers\DetailHandler;


class TeachersAttandanceApiService extends ApiService
{
    protected static string | null $resource = TeachersAttandanceResource::class;

    public static function handlers(): array
    {
        return [
            Handlers\CreateHandler::class,
            // Handlers\UpdateHandler::class,
            // Handlers\DeleteHandler::class,
            // Handlers\PaginationHandler::class,
            // Handlers\DetailHandler::class
        ];
    }
}