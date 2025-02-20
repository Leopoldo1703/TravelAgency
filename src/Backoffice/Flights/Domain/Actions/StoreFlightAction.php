<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\Domain\Actions;

use Illuminate\Validation\ValidationException;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Lightit\Backoffice\Flights\Domain\DataTransferObjects\FlightDto;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

class StoreFlightAction
{
    public function execute(FlightDto $flightDto): Flight
    {
        $airline = Airline::findOrFail($flightDto->getAirlineId());

        if (
            ! $airline->cities->contains($flightDto->getOriginId())
            || ! $airline->cities->contains($flightDto->getDestinationId())
        ) {
            throw ValidationException::withMessages([
                'cities' => 'This airline does not operate in one or both selected cities.',
            ]);
        }

        return Flight::create([
            'airline_id' => $flightDto->getAirlineId(),
            'origin_id' => $flightDto->getOriginId(),
            'destination_id' => $flightDto->getDestinationId(),
            'departure' => $flightDto->getDeparture(),
            'arrival' => $flightDto->getArrival(),
        ]);
    }
}
