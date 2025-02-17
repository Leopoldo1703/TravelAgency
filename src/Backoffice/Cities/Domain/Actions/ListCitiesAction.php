<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Cities\Domain\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Lightit\Backoffice\Cities\Domain\Models\City;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ListCitiesAction
{
    /**
     * @return Collection<int, Model>
     */
    public function execute(): Collection
    {
        /** @var QueryBuilder $query */
        $query = QueryBuilder::for(City::class)
            ->withCount(['departures', 'arrivals'])
            ->allowedFilters([
                AllowedFilter::callback('airline', function ($query, $value) {
                    $query->whereHas('departures', function ($q) use ($value) {
                        $q->where('airline_id', $value);
                    })->orWhereHas('arrivals', function ($q) use ($value) {
                        $q->where('airline_id', $value);
                    });
                }),
            ])
            ->allowedSorts('name')
            ->defaultSort('id');

        return $query->get();
    }
}
