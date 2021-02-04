<?php
namespace Webid\Cms\Modules\Articles\Nova\Layouts;

use Infinety\Filemanager\FilemanagerField;
use Webid\TranslatableTool\Translatable;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class ImageLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'image';

    /**
     * @return string|null
     */
    public function title()
    {
        return __('Image section');
    }

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            FilemanagerField::make(__('Image'))
                ->filterBy('images')
                ->displayAsImage(),

            Translatable::make(__('Balise alt'))
                ->singleLine(),
        ];
    }
}
