<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\Domain\Actions;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Lightit\Backoffice\Flights\Domain\Models\Flight;
use Spatie\QueryBuilder\QueryBuilder;

class ListFlightsAction
{
    /**
     * @return Collection<int, Flight>
     */
    public function execute(): Collection
    {
        return Flight::with(['airline', 'originCity', 'destinationCity'])->get();
    }
}
