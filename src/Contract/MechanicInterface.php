<?php

declare(strict_types=1);

namespace App\Contract;

use App\Entity\Car;

interface MechanicInterface
{
    /**
     * @return array<string, int|string|null>
     */
    public function analyze(Car $car): array;
}
