<?php
namespace Webid\Cms\Modules\Articles\Nova\Layouts;

use Infinety\Filemanager\FilemanagerField;
use Laravel\Nova\Fields\Hidden;
use Laravel\Nova\Fields\Select;
use Webid\TranslatableTool\Translatable;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class TextVideoLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'text-video';

    /**
     * @return string|null
     */
    public function title()
    {
        return __('Text & video section');
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

            Select::make(__('Text position'))
                ->options([
                    'left' => __('Left'),
                    'right' => __('Right')
                ]),

            FilemanagerField::make(__('Video'))
                ->filterBy('videos')
                ->displayAsImage(),
        ];
    }
}
