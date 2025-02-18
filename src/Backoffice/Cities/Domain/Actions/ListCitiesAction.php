<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Cities\Domain\Actions;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Lightit\Backoffice\Cities\Domain\Models\City;


class ListCitiesAction
{
    /**
     * @return Collection<int, Model>
     */
    public function execute(): Collection
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
        ->allowedSorts('name')
        ->defaultSort('id');
        return $query->get();
    }
}
