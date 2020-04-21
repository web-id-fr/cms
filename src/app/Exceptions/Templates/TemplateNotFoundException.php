<?php

namespace Webid\Cms\Src\App\Exceptions\Templates;

use Exception;
use Throwable;

class TemplateNotFoundException extends Exception
{
    /**
     * @var string $templatePath The name of the template we were looking for
     */
    public $templatePath = '';

    /**
     * TemplateNotFoundException constructor.
     *
     * @param string         $templatePath
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct(string $templatePath, string $message = "", int $code = 0, Throwable $previous = null)
    {
        $this->templatePath = $templatePath;

        $message = empty($message) ? "The template $this->$templatePath was not found." : $message;

        parent::__construct($message, $code, $previous);
    }
}
