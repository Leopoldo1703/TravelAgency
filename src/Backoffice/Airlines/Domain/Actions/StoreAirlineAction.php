<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Airlines\Domain\Actions;

use Lightit\Backoffice\Airlines\Domain\DataTransferObjects\AirlineDto;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;

class StoreAirlineAction
{
    public function execute(AirlineDto $airlineDto): Airline
    {
        $airline = new Airline([
            'name' => $airlineDto->getName(),
            'description' => $airlineDto->getDescription(),
        ]);

        $airline->save();

        return $airline;
    }
}
