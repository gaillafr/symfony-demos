<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\MechanicInterface;
use App\Entity\Car;

class Mechanic implements MechanicInterface
{
    /**
     * @return array<string, int|string|null>
     */
    public function analyze(Car $car): array
    {
        return [
            '#' => $car->getId(),
            'Brand' => $car->getBrand(),
        ];
    }
}
