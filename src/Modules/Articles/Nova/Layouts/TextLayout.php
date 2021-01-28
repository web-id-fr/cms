<?php
namespace Webid\Cms\Modules\Articles\Nova\Layouts;

use Laravel\Nova\Fields\Hidden;
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
        $layoutViewPath = config('articles.default_paths.articles');

        return [
            Hidden::make('Layout')->default("$layoutViewPath.$this->name"),

            Translatable::make(__('Text'))
                ->trix()
                ->asHtml(),
        ];
    }
}
