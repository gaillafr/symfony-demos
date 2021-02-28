<?php

declare(strict_types=1);

namespace App\Contract;

interface DataTransformerInterface
{
    /**
     * @param mixed $data
     */
    public function supportsTransformation($data, string $to, array $context = []): bool;

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    public function transform($data, string $to, array $context = []);
}
