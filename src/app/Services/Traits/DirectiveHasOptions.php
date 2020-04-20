<?php

namespace Webid\Cms\Src\App\Services\Traits;

trait DirectiveHasOptions
{
    /**
     * Transforme le paramètre passé à la directive en tableau, à partir d'une chaîne de caractères
     *
     * @param string $optionsString
     *
     * @return array
     */
    protected function extractOptions(string $optionsString): array
    {
        try {
            // todo : ici faire autrement qu'avec "eval" ...
            $array = eval("return $optionsString;");

            if (!is_array($array)) {
                $array = [];
            }
        } catch (\Throwable $throwable) {
            info($throwable->getMessage());

            $array = [];
        }

        return $array;
    }
}
