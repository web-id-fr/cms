<?php
namespace Webid\Cms\Modules\Articles\Nova\Layouts;

use Laravel\Nova\Fields\Select;
use Webid\Cms\Modules\Slideshow\Repositories\SlideshowRepository;
use Whitecube\NovaFlexibleContent\Layouts\Layout;

class SlideshowLayout extends Layout
{
    /** @var string */
    protected $name = 'slideshow';

    /**
     * @return string|null
     */
    public function title()
    {
        return __('Slideshow section');
    }

    /**
     * Get the fields displayed by the layout.
     *
     * @return array
     */
    public function fields()
    {
        $slideshowRepository = app(SlideshowRepository::class);
        $slideshows = $slideshowRepository->all();
        $slideshows = $slideshows->reduce(function ($values, $slideshow) {
            $values[$slideshow->id] = $slideshow->title;
            return $values;
        }, []);

        return [
            Select::make('Slideshow', 'slideshow_select')
                ->options($slideshows)
                ->displayUsingLabels()
                ->nullable()
        ];
    }
}
