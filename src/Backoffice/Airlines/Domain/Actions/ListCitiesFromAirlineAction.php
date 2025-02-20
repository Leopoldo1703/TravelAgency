<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Airlines\Domain\Actions;

use Illuminate\Database\Eloquent\Collection;
use Lightit\Backoffice\Airlines\Domain\Models\Airline;
use Lightit\Backoffice\Cities\Domain\Models\City;

class ListCitiesFromAirlineAction
{
    /**
     * @return Collection<int, City>
     */
    public function execute(Airline $airline): Collection
    {
        return $airline->cities()->get();
    }
}
