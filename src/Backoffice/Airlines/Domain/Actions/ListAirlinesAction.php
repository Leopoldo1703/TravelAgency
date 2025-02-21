<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Airlines\Domain\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ListAirlinesAction
{
    /**
     * @return LengthAwarePaginator<Model>
     */
    public function execute(): LengthAwarePaginator
    {
        /** @var QueryBuilder $query */
        $query = QueryBuilder::for(Airline::class)
            ->withCount('flights');

        $query ->allowedFilters([
                AllowedFilter::callback('active_flights', function (Builder $query, int $value) {
                    $query->whereRaw(
                        '(select count(*) from flights where flights.airline_id = airlines.id) = ?',
                        [$value]
                    );
                }),

                AllowedFilter::callback('city', function (Builder $query, int $value) {
                    $query->whereHas('flights', function (Builder $q) use ($value) {
                        $q->where('origin_id', $value)
                        ->orWhere('destination_id', $value);
                    });
                }),
            ])
            ->allowedSorts(['name', 'flights_count'])
            ->defaultSort('id');

        return $query->paginate(10);
    }
}
