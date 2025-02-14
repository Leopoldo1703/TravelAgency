<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\Domain\Actions;

use Lightit\Backoffice\Flights\Domain\DataTransferObjects\FlightDto;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

class UpdateFlightAction
{
    public function execute(Flight $flight, FlightDto $flightDto): Flight
    {
        $flight->fill(array_filter([
            'airline_id' => $flightDto->getAirlineId(),
            'origin_id' => $flightDto->getOriginId(),
            'destination_id' => $flightDto->getDestinationId(),
            'departure' => $flightDto->getDeparture(),
            'arrival' => $flightDto->getArrival(),
        ], fn($value) => !is_null($value)));

        $flight->save();

        return $flight;
    }
}
