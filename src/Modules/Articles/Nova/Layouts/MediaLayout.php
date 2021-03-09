<?php
namespace Webid\Cms\Modules\Articles\Nova\Layouts;

use Infinety\Filemanager\FilemanagerField;
use Webid\TranslatableTool\Translatable;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class MediaLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'media';

    /**
     * @return array|string|null
     */
    public function title()
    {
        return __('Button media section');
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

            FilemanagerField::make(__('Media'), 'media')
                ->displayAsImage(),
        ];
    }
}
