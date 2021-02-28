<?php

declare(strict_types=1);

namespace App\Tests\Unit\DataTransformer;

use App\DataTransformer\UserTransformer;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTransformerTest extends TestCase
{
    private const SAMPLE_USER_DATA = [
        'email' => 'j.doe@example.com',
        'username' => 'john.doe',
    ];

    public function testTransformerSupportsArray(): void
    {
        $transformer = new UserTransformer();

        $support = $transformer->supportsTransformation(self::SAMPLE_USER_DATA, User::class);

        self::assertTrue($support);
    }

    public function testTransformerDoesNotSupportUser(): void
    {
        $transformer = new UserTransformer();

        $support = $transformer->supportsTransformation(new User(), User::class);

        self::assertFalse($support);
    }
}
