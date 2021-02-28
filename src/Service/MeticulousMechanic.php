<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\MechanicInterface;
use App\Entity\Car;

class MeticulousMechanic implements MechanicInterface
{
    private MechanicInterface $mechanic;

    public function __construct(MechanicInterface $mechanic)
    {
        $this->mechanic = $mechanic;
    }

    /**
     * @return array<string, int|string|null>
     */
    public function analyze(Car $car): array
    {
        $details = $this->mechanic->analyze($car);

        return \array_merge($details, [
            'Color' => $car->getColor(),
            'Model' => $car->getModel(),
        ]);
    }
}
