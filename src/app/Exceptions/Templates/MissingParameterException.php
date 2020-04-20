<?php

namespace Webid\Cms\Src\App\Exceptions\Templates;

use Exception;
use Throwable;

class MissingParameterException extends Exception
{
    /**
     * MissingParameterException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message = 'A parameter is missing', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
