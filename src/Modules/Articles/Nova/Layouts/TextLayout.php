<?php
namespace Webid\Cms\Modules\Articles\Nova\Layouts;

use Webid\TranslatableTool\Translatable;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class TextLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'text';

    /**
     * @return string|null
     */
    public function title()
    {
        return __('Text section');
    }

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Translatable::make(__('Text'))
                ->trix()
                ->asHtml(),
        ];
    }
}
