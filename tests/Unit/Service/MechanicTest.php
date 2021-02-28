<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Entity\Car;
use App\Service\Mechanic;
use PHPUnit\Framework\TestCase;

class MechanicTest extends TestCase
{
    private const SAMPLE_CAR_DATA = [
        'brand' => 'Delta',
        'color' => 'Blue',
        'model' => 'G12',
    ];

    public function testMechanic(): void
    {
        $car = (new Car())
            ->setBrand(self::SAMPLE_CAR_DATA['brand'])
            ->setColor(self::SAMPLE_CAR_DATA['color'])
            ->setModel(self::SAMPLE_CAR_DATA['model'])
        ;

        $details = (new Mechanic())
            ->analyze($car)
        ;

        self::assertArrayHasKey('#', $details);
        self::assertArrayHasKey('Brand', $details);
    }
}
