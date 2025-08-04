<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Airlines\Domain\Actions;

use Lightit\Backoffice\Airlines\Domain\Models\Airline;

class GetAirlineAction
{
    public function execute(Airline $airline): Airline
    {
        return $airline->loadCount('flights');
    }
}
