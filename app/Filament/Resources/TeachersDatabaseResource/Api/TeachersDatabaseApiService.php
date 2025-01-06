<?php
namespace App\Filament\Resources\TeachersDatabaseResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\TeachersDatabaseResource;
use Illuminate\Routing\Router;


class TeachersDatabaseApiService extends ApiService
{
    protected static string | null $resource = TeachersDatabaseResource::class;

    public static function handlers() : array
    {
        return [
            Handlers\CreateHandler::class,
            Handlers\UpdateHandler::class,
            Handlers\DeleteHandler::class,
            Handlers\PaginationHandler::class,
            Handlers\DetailHandler::class
        ];

    }
}
