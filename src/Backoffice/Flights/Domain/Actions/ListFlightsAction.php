<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\Domain\Actions;

use Illuminate\Database\Eloquent\Collection;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

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
