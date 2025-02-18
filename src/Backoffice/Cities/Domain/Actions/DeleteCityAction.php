<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Cities\Domain\Actions;

use Lightit\Backoffice\Cities\Domain\Models\City;

class DeleteCityAction
{
    public function execute(City $city): void
    {
        $city->delete();
    }
}
