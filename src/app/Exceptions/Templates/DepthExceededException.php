<?php

namespace Webid\Cms\App\Exceptions\Templates;

use Exception;
use Throwable;

class DepthExceededException extends Exception
{
    /**
     * DepthExceededException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(
        $message = "The maximal allowed depth was reached while scanning the templates.",
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
