<?php
namespace Webid\Cms\Modules\Articles\Nova\Layouts;

use Infinety\Filemanager\FilemanagerField;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class VideoLayout extends Layout
{
    /**
     * The layout's unique identifier
     *
     * @var string
     */
    protected $name = 'video';

    /**
     * @return string|null
     */
    public function title()
    {
        return __('Video section');
    }

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        return [
            FilemanagerField::make(__('Video'))
                ->filterBy('videos')
                ->displayAsImage(),
        ];
    }
}
