<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Cities\Domain\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Lightit\Backoffice\Cities\Domain\Models\City;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ListCitiesAction
{
    /**
     * @return LengthAwarePaginator<Model>
     */
    public function execute(): LengthAwarePaginator
    {
        /** @var QueryBuilder $query */
        $query = QueryBuilder::for(City::query())
        ->withCount(['departures', 'arrivals']);

        $query->allowedFilters([
            AllowedFilter::callback('airline', function (Builder $query, $value) {
                $query->whereHas('departures', function (Builder $q) use ($value) {
                    $q->where('airline_id', $value);
                })->orWhereHas('arrivals', function (Builder $q) use ($value) {
                    $q->where('airline_id', $value);
                });
            }),
        ])
        ->allowedSorts(['name', 'id'])
        ->defaultSort('id');

        return $query->paginate(10);
    }
}
