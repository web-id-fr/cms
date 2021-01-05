<?php

namespace Webid\Cms\App\Services\MenuBladeDirective;

use Webid\Cms\App\Exceptions\Templates\MissingParameterException;
use Webid\Cms\App\Services\Traits\DirectiveHasOptions;
use InvalidArgumentException;

class Menu
{
    use DirectiveHasOptions;

    /** @var string La directive Blade d'origine, utilisée pour générer cet objet */
    public $originalExpression;

    /** @var string $menuID L'ID du menu */
    public $menuID;

    /** @var string $label Le label du menu dans le BO */
    public $label;

    /** @var array $options Un tableau contenant tous les paramètres optionels supplémentaires */
    public $options = [];

    /**
     * Menu constructor.
     *
     * @param string $zoneParams
     *
     * @throws MissingParameterException
     */
    public function __construct(string $zoneParams)
    {
        $this->originalExpression = $zoneParams;

        $zoneParams = explode(',', $zoneParams, 3);

        $options = $this->extractOptions($zoneParams[2] ?? '');

        if (empty($zoneParams)) {
            throw new MissingParameterException("The @menu directive needs at least one attribute.");
        }
        if (empty($zoneParams[0]) || !is_string($zoneParams[0])) {
            throw new MissingParameterException("The @menu first attribute is missing or invalid.");
        }
        if (!empty($zoneParams[1]) && !is_string($zoneParams[1])) {
            throw new InvalidArgumentException("The @menu second attribute is invalid.");
        }

        $this->menuID = trim(data_get($zoneParams, 0, ''), ' "\'');
        $this->label = trim(data_get($zoneParams, 1, ucwords(str_unslug($this->menuID))), ' "\'');
        $this->options = $options;
    }
}
