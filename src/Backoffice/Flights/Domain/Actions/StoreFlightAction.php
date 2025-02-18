<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\Domain\Actions;

use Lightit\Backoffice\Flights\Domain\DataTransferObjects\FlightDto;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

class StoreFlightAction
{
    public function execute(FlightDto $flightDto): Flight
    {
        return Flight::create([
            'airline_id' => $flightDto->getAirlineId(),
            'origin_id' => $flightDto->getOriginId(),
            'destination_id' => $flightDto->getDestinationId(),
            'departure' => $flightDto->getDeparture(),
            'arrival' => $flightDto->getArrival(),
        ]);
    }
}
