<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\App\Transformers;

use Flugg\Responder\Transformers\Transformer;
use Lightit\Backoffice\Flights\Domain\Models\Flight;

class FlightTransformer extends Transformer
{
    /**
     * @return array{id: int, airline_id: int, origin_id: int, destination_id: int, departure: string, arrival: string}
     */
    public function transform(Flight $flight): array
    {
        return [
            'id' => $flight->id,
            'airline_id' => $flight->airline_id,
            'origin_id' => $flight->origin_id,
            'destination_id' => $flight->destination_id,
            'departure' => $flight->departure,
            'arrival' => $flight->arrival,
        ];
    }
}
