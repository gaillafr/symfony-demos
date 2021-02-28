<?php

declare(strict_types=1);

namespace App\Exception;

class DataTransformerNotFoundException extends \Exception
{
    /**
     * @param string $message
     * @param int    $code
     */
    public function __construct($message = null, $code = 0, \Throwable $previous = null)
    {
        $message = 'Unable to find the requested data transformer';

        parent::__construct($message, $code, $previous);
    }
}
