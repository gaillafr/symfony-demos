<?php

declare(strict_types=1);

namespace App\DataTransformer;

use App\Contract\DataTransformerInterface;
use App\Entity\User;

class UserTransformer implements DataTransformerInterface
{
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof User) {
            return false;
        }

        return User::class === $to && (null === ($context['class'] ?? null));
    }

    public function transform($data, string $to, array $context = []): User
    {
        $user = new User();

        $user->setEmail($data['email']);
        $user->setUsername($data['username']);

        return $user;
    }
}
