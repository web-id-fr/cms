<?php

namespace Webid\Cms\Src\App\Exceptions\Templates;

use Exception;
use Throwable;

class DuplicateIdException extends Exception
{
    /**
     * DuplicateIdException constructor.
     *
     * @param array          $ids
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(array $ids, string $message = '', int $code = 0, Throwable $previous = null)
    {
        $message = empty($message) ? "The following IDs are duplicates : " . join(', ', $ids) : $message;

        parent::__construct($message, $code, $previous);
    }
}
