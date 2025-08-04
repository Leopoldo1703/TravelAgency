<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Flights\Domain\Actions;

use Lightit\Backoffice\Flights\Domain\Models\Flight;

class DeleteFlightAction
{
    public function execute(Flight $flight): void
    {
        $flight->delete();
    }
}
