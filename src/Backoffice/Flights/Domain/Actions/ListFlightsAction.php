<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\Domain\Actions;

use Illuminate\Pagination\LengthAwarePaginator;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

class ListFlightsAction
{
    /**
     * @return LengthAwarePaginator<Flight>
     */
    public function execute(): LengthAwarePaginator
    {
        return Flight::with(['airline', 'originCity', 'destinationCity'])->paginate(10);
    }
}
