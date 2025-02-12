<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Cities\Domain\Actions;

use Lightit\Backoffice\Cities\Domain\DataTransferObjects\CityDto;
use Lightit\Backoffice\Cities\Domain\Models\City;

class StoreCityAction
{
    public function execute(CityDto $cityDto): City
    {
        $city = new City([
            'name' => $cityDto->getName(),
        ]);

        $city->save();

        return $city;
    }
}
