<?php
namespace App\Filament\Resources\TreatmentResource\Api;

use Rupadana\ApiService\ApiService;
use App\Filament\Resources\TreatmentResource;
use Illuminate\Routing\Router;


class TreatmentApiService extends ApiService
{
    protected static string | null $resource = TreatmentResource::class;

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
