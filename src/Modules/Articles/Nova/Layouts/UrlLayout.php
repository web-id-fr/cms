<?php

namespace Webid\Cms\Modules\Articles\Nova\Layouts;

use Webid\TranslatableTool\Translatable;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class UrlLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'url';

    /**
     * @return array|string|null
     */
    public function title()
    {
        return __('Button url section');
    }

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Translatable::make(__('CTA Name'), 'cta_name')
                ->singleLine(),

            Translatable::make(__('CTA link'), 'url')
                ->singleLine(),
        ];
    }
}
