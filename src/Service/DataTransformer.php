<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\DataTransformerInterface;
use App\Exception\DataTransformerNotFoundException;

final class DataTransformer
{
    /**
     * @var DataTransformerInterface[]
     */
    private array $transformers = [];

    public function __construct(iterable $transformers)
    {
        \array_push($this->transformers, ...$transformers);
    }

    /**
     * @param mixed $data
     *
     * @throws DataTransformerNotFoundException
     *
     * @return mixed
     */
    public function transform($data, string $to, array $context = [])
    {
        $transformer = $this->getTransformer($data, $to, $context);

        if (null === $transformer) {
            throw new DataTransformerNotFoundException();
        }

        return $transformer->transform($data, $to, $context);
    }

    /**
     * @param mixed $data
     */
    private function getTransformer($data, string $to, array $context = []): ?DataTransformerInterface
    {
        foreach ($this->transformers as $transformer) {
            if ($transformer->supportsTransformation($data, $to, $context)) {
                return $transformer;
            }
        }

        return null;
    }
}
