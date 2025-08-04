<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Cities\App\Transformers;

use Flugg\Responder\Transformers\Transformer;
use Lightit\Backoffice\Cities\Domain\Models\City;

class CityTransformer extends Transformer
{
    /**
     * @return array{id: int, name: string, number_of_departures: int, number_of_arrivals: int}
     */
    public function transform(City $city): array
    {
        return [
            'id' => $city->id,
            'name' => $city->name,
            'number_of_departures' => $city->departures_count ?? 0,
            'number_of_arrivals' => $city->arrivals_count ?? 0,
        ];
    }
}
