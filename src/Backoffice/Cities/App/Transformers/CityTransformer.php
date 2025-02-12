<?php

declare(strict_types=1);

namespace Lightit\Backoffice\Cities\App\Transformers;

use Flugg\Responder\Transformers\Transformer;
use Lightit\Backoffice\Cities\Domain\Models\City;

class CityTransformer extends Transformer
{
    /**
     * @return array{id: int, name: string}
     */
    public function transform(City $city): array
    {
        return [
            'id' => $city->id,
            'name' => $city->name,
        ];
    }
}
