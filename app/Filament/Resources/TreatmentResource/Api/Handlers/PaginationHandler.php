<?php
namespace App\Filament\Resources\TreatmentResource\Api\Handlers;

use Illuminate\Http\Request;
use Rupadana\ApiService\Http\Handlers;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filament\Resources\TreatmentResource;

class PaginationHandler extends Handlers {
    public static string | null $uri = '/';
    public static string | null $resource = TreatmentResource::class;


    public function handler()
    {
        $model = static::getModel();

        $query = QueryBuilder::for($model)
        ->allowedFields($model::$allowedFields ?? [])
        ->allowedSorts($model::$allowedSorts ?? [])
        ->allowedFilters($model::$allowedFilters ?? [])
        ->paginate(request()->query('per_page'))
        ->appends(request()->query());

        return static::getApiTransformer()::collection($query);
    }
}