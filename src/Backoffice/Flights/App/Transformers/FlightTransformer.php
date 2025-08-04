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
            'airline_name' => $flight->airline->name,
            'origin_id' => $flight->origin_id,
            'origin_name' => $flight->originCity ? $flight->originCity->name : null,
            'destination_id' => $flight->destination_id,
            'destination_name'=> $flight->destinationCity ? $flight->destinationCity->name : null,
            'departure' => $flight->departure->format('Y-m-d H:i:s'),
            'arrival' => $flight->arrival->format('Y-m-d H:i:s'),
        ];
    }
}
