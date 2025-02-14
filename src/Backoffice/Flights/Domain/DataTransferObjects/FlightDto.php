<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\Domain\DataTransferObjects;

use Carbon\Carbon;

class FlightDto
{
    public function __construct(
        private readonly int $airlineId,
        private readonly int $originId,
        private readonly int $destinationId,
        private readonly Carbon $departure,
        private readonly Carbon $arrival,
    ) {
    }

    public function getAirlineId(): int
    {
        return $this->airlineId;
    }

    public function getOriginId(): int
    {
        return $this->originId;
    }

    public function getDestinationId(): int
    {
        return $this->destinationId;
    }

    public function getDeparture(): Carbon
    {
        return $this->departure;
    }

    public function getArrival(): Carbon
    {
        return $this->arrival;
    }
}
