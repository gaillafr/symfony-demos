<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Contract\DataTransformerInterface;
use App\DataTransformer\UserTransformer;
use App\Entity\User;
use App\Exception\DataTransformerNotFoundException;
use App\Service\DataTransformer;
use PHPUnit\Framework\TestCase;

class DataTransformerTest extends TestCase
{
    private const SAMPLE_USER_DATA = [
        'email' => 'j.doe@example.com',
        'username' => 'john.doe',
    ];

    public function testUserDataTransformer(): void
    {
        $fooTransformer = $this->getMockBuilder(UserTransformer::class)
            ->getMock()
        ;

        $user = (new User())
            ->setEmail(self::SAMPLE_USER_DATA['email'])
            ->setUsername(self::SAMPLE_USER_DATA['username'])
        ;

        $fooTransformer
            ->method('transform')
            ->with(self::SAMPLE_USER_DATA, User::class)
            ->willReturn($user)
        ;

        $fooTransformer
            ->method('supportsTransformation')
            ->with(self::SAMPLE_USER_DATA, User::class)
            ->willReturn(true)
        ;

        $transformers = new \ArrayObject([$fooTransformer]);
        $dataTransformer = new DataTransformer($transformers->getIterator());

        $data = $dataTransformer->transform(self::SAMPLE_USER_DATA, User::class);

        self::assertInstanceOf(User::class, $data);
    }

    public function testDataTransformerNotFound(): void
    {
        $fooTransformer = $this->getMockBuilder(DataTransformerInterface::class)
            ->getMock()
        ;

        $fooTransformer
            ->method('supportsTransformation')
            ->with('foo', \stdClass::class)
            ->willReturn(false)
        ;

        $transformers = new \ArrayObject([$fooTransformer]);
        $dataTransformer = new DataTransformer($transformers->getIterator());

        $this->expectException(DataTransformerNotFoundException::class);
        $dataTransformer->transform('foo', \stdClass::class);
    }
}
