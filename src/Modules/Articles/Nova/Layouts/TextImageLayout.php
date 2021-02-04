<?php
namespace Webid\Cms\Modules\Articles\Nova\Layouts;

use Infinety\Filemanager\FilemanagerField;
use Laravel\Nova\Fields\Select;
use Webid\TranslatableTool\Translatable;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class TextImageLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'text-image';

    /**
     * @return string|null
     */
    public function title()
    {
        return __('Text & image section');
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

            Select::make(__('Text position'))
                ->options([
                    'left' => __('Left'),
                    'right' => __('Right')
                ]),

            FilemanagerField::make(__('Image'))
                ->filterBy('images')
                ->displayAsImage(),

            Translatable::make(__('Balise alt'))
                ->singleLine(),
        ];
    }
}
